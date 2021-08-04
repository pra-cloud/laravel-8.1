<?php

namespace App\Http\Controllers;

use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;
use Hyperzod\HyperzodServiceFunctions\Traits\HelpersServiceTrait;
use Hyperzod\HyperzodServiceFunctions\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Location\Coordinate;
use Location\Polygon;
use Location\Distance\Vincenty;

class ServiceableAreaController extends Controller
{
    use ApiResponseTrait;
    use SettingsServiceTrait;
    use HelpersServiceTrait;

    public function checkIfPresentOrnot(Request $request)
    {
        $serviceable_or_not = $this->check($request);
        if (!$serviceable_or_not['success']) {
            return $this->errorResponse("Tenant not serviceable in your area.");
        }
        return $this->successResponse("Tenant Serviceable", $serviceable_or_not);
    }

    public function check(Request $request) // : array $serviceable_or_not
    {
        $validated_values = $this->validateUserInput($request);

        $user_lat_long = $validated_values['user_lat_long'];
        $country = $validated_values['country'];

        $serviceable_area_settings = $this->settingsByKeys('tenant', ['serviceable_area'], null, null, 1, null);

        // If the serviceable_area settings are not set, the tenant is serviceable globally, always return true.
        if ($serviceable_area_settings == false){
            $serviceable_or_not['success'] = true;
            return $serviceable_or_not;
        }

        $serviceable_area_settings = $serviceable_area_settings->toArray()['serviceable_area'];

        $countries = collect($serviceable_area_settings)->where('method', 'country')->toArray();

        // Check if the tenant is serviceable in the user given country
        if (!empty($country)) {
            $serviceable_or_not = $this->checkByCountry($countries, $country);
            if ($serviceable_or_not['success']) {
                return $serviceable_or_not;
            }
        }


        foreach ($serviceable_area_settings as $setting) {

            if ($setting['method'] == 'radius') {
                $serviceable_area_status = $this->checkByRadius($setting, $user_lat_long);
                if ((isset($serviceable_area_status['success']) && $serviceable_area_status['success'])) {
                    return $serviceable_area_status;
                }
            }

            if ($setting['method'] == 'geofence') {
                $serviceable_area_status = $this->checkByGeofence($setting, $user_lat_long);
                if (isset($serviceable_area_status['success']) && $serviceable_area_status['success']) {
                    return $serviceable_area_status;
                }
            }
        }

        // Purpose: Return the message below if the user location falls out of the serviceable area.
        $serviceable_or_not['success'] = false;
        return $serviceable_or_not;
    }

    public function checkByCountry($countries_array_data, $user_input_country) // : array $serviceable_or_not
    {
        foreach ($countries_array_data as $countries) {
            $countries_array = $countries['value'];
            $country_status = in_array($user_input_country, $countries_array);
            if ($country_status) {
                $serviceable_or_not['name'] = $countries['name'];
                $serviceable_or_not['group_ids'] = $countries['group_ids'] ?? null;
                $serviceable_or_not['success'] = $country_status;
                return $serviceable_or_not;
            }
        }
        $serviceable_or_not['success'] = false;
        return $serviceable_or_not;
    }

    // Inside a foreach loop
    public function checkByRadius($setting, $user_lat_long) // : array $serviceable_or_not
    {
        $destination_location = $setting['value']['location'];
        $radius = $setting['value']['radius'];
        $scale = $setting['value']['scale'];

        $radius_in_metres = $this->getRadiusInMetres($scale, $radius);

        $user_lat_long = new Coordinate($user_lat_long[0], $user_lat_long[1]);
        $destination_location = new Coordinate($destination_location[0], $destination_location[1]);

        $distance = $user_lat_long->getDistance($destination_location, new Vincenty());

        // User falls within the radius
        if ($distance <= $radius_in_metres) {

            $serviceable_or_not['group_ids'] = $setting['group_ids'] ?? null;
            $serviceable_or_not['success'] = true;
            return $serviceable_or_not;
        }

        // User DOES NOT fall within the radius
        if ($distance > $radius_in_metres) {
            $serviceable_or_not['group_ids'] = $setting['group_ids'] ?? null;
            $serviceable_or_not['success'] = false;
            return $serviceable_or_not;
        }
    }

    // Inside a foreach loop
    public function checkByGeofence($setting, $user_lat_long) // : array $serviceable_or_not
    {
        $geofence_coordinates = $setting['value'];
        $user_lat_long = new Coordinate($user_lat_long[0], $user_lat_long[1]);

        foreach ($geofence_coordinates as $geofence_coordinate) {
            $geofence = $this->returnGeofence($geofence_coordinate);
            $user_point_inside[] = $geofence->contains($user_lat_long);
        }

        // Purpose: Check if the user falls in any of the geofences of the tenant, return true if yes
        if (in_array(true, $user_point_inside)) {
            $serviceable_or_not['group_ids'] = $setting['group_ids'] ?? null;
            $serviceable_or_not['success'] = true;
            return $serviceable_or_not;
        }
        $serviceable_or_not['success'] = false;
        return $serviceable_or_not;
    }

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

    public function validateUserInput($request)
    {
        $country_codes = $this->fetchCountries();;
        $country_codes = collect($country_codes)->pluck('code')->toArray();

        Validator::extend('country_exists', function ($attribute, $value, $parameters) use ($country_codes) {
            $country_present = in_array($value, $country_codes);
            if (!$country_present) {
                return false;
            }

            return true;
        }, "Invalid country code(s)");

        $validator = Validator::make($request->all(), [
            'country' => 'nullable|country_exists',
            'user_lat_long' => ["array", "min:2", "max:2"],
            'user_lat_long.*' => 'numeric'
        ]);

        if ($validator->fails()) {
            $validation_errors = $validator->errors();
            
            throw new \Exception($validation_errors);
        }

        $validated_values = $validator->validated();
        return $validated_values;
    }
}
