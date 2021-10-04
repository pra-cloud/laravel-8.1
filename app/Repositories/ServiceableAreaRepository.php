<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Validator;
use Location\Coordinate;
use Location\Polygon;
use Location\Distance\Vincenty;
use App\Rules\CountryExists;

trait ServiceableAreaRepository
{
    public function validateUserInput($request) // : array $validated_values || throw new Exception
    {
        $rules = [
            'country' => ['nullable', 'string', new CountryExists],
            'user_lat_long' => ["array", "min:2", "max:2"],
            'user_lat_long.*' => 'numeric'
        ];

        $validator = Validator::make($request->all(), $rules);
        $this->throwExceptionForErrors($validator);
        $validated = $validator->validated();

        return $validated;
    }

    public function checkByCountry($countries_array_data, $user_input_country) // : array $area
    {
        foreach ($countries_array_data as $countries) {

            if ($countries['active'] == true) {

                $countries_array = $countries['value'];
                $country_status = in_array($user_input_country, $countries_array);
                if ($country_status) {
                    $area['name'] = $countries['name'];
                    $area['group_ids'] = $countries['group_ids'] ?? [];
                    $area['is_serviceable'] = true;
                    return $area;
                }
            }

            if ($countries['active'] == false) {
                $area['is_serviceable'] = false;
                return $area;
            }
        }
        $area['is_serviceable'] = false;
        return $area;
    }

    // Inside a foreach loop
    public function checkByRadius($setting, $user_lat_long) // : array $area
    {
        if ($setting['active'] == true) {
            $destination_location = $setting['value']['location'];
            $radius = $setting['value']['radius'];
            $scale = $setting['value']['scale'];

            $radius_in_metres = $this->getRadiusInMetres($scale, $radius);

            $user_lat_long = new Coordinate($user_lat_long[0], $user_lat_long[1]);
            $destination_location = new Coordinate($destination_location[0], $destination_location[1]);

            $distance = $user_lat_long->getDistance($destination_location, new Vincenty());
            // User falls within the radius
            if ($distance <= $radius_in_metres) {

                $area['name'] = $setting['name'];
                $area['group_ids'] = $setting['group_ids'] ?? [];
                $area['is_serviceable'] = true;
                return $area;
            }

            // User DOES NOT fall within the radius
            if ($distance > $radius_in_metres) {
                $area['is_serviceable'] = false;
                return $area;
            }
        }

        if ($setting['active'] == false) {
            $area['is_serviceable'] = false;
            return $area;
        }
    }

    // Inside a foreach loop
    public function checkByGeofence($setting, $user_lat_long) // : array $area
    {
        if ($setting['active'] == true) {
            $geofence_coordinates = $setting['value'];
            $user_lat_long = new Coordinate($user_lat_long[0], $user_lat_long[1]);

            foreach ($geofence_coordinates as $geofence_coordinate) {
                $geofence = $this->returnGeofence($geofence_coordinate);
                $user_point_inside[] = $geofence->contains($user_lat_long);
            }

            // Purpose: Check if the user falls in any of the geofences of the tenant, return true if yes
            if (in_array(true, $user_point_inside)) {
                $area['name'] = $setting['name'];
                $area['group_ids'] = $setting['group_ids'] ?? [];
                $area['is_serviceable'] = true;
                return $area;
            }
            $area['is_serviceable'] = false;
            return $area;
        }

        if ($setting['active'] == false) {
            $area['is_serviceable'] = false;
            return $area;
        }
    }

    // Helpers
    public function returnGeofence($coordinates_array) // : array $geofence
    {
        $geofence = new Polygon();
        foreach ($coordinates_array as $coordinates) {
            $coordinates = array_values($coordinates);
            $geofence->addPoint(new Coordinate($coordinates[0], $coordinates[1]));
        }
        return $geofence;
    }

    public function getRadiusInMetres($scale, $radius) // : $radius_in_metres
    {
        if ($scale == 'km') {
            $radius_in_metres =  $radius * 1000;
            return $radius_in_metres;
        }
        if ($scale == 'mi') {
            $radius_in_metres = $radius * 1609.34;
            return $radius_in_metres;
        }
    }

    public function getSettings()
    {
        $setting = $this->settingsByKeys('tenant', ['serviceable_area'], null, null, 1, null);
        if ($setting->isEmpty()) return false;

        return $setting->toArray()['serviceable_area'];
    }

    public function isServiceableGlobally($validated, $settings) // : $area
    {
         // Setting service: 'serviceable_area' setting is not set
         // Serviceable globally
         if ($settings == false) {
            $area['name'] = "Serviceable globally";
            $area['is_serviceable'] = true;
            return $area;
        }

        if (!isset($validated['country'])) {
            $area['name'] = "Serviceable globally";
            $area['is_serviceable'] = true;
            return $area;
        }

        // A specific country is set
        return false;
    }

    public function isServiceableInRequestedCountry($validated, $settings)
    {
        if (isset($validated['country'])) {

            $countries = collect($settings)->where('method', 'country')->toArray();
            $area = $this->checkByCountry($countries, $validated['country']);

            if ($area['is_serviceable']) {
                return $area;
            }
        }

        // Not serviceable in the requested country
        return false;

    }
}
