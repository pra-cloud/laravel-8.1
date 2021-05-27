<?php

namespace App\Http\Controllers;


use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;
use Hyperzod\HyperzodServiceFunctions\Traits\HelpersServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Location\Coordinate;
use Location\Polygon;
use Location\Distance\Vincenty;

class ServiceableAreaController extends Controller
{
    use SettingsServiceTrait;
    use HelpersServiceTrait;

    public function check(Request $request)
    {
        $validated_values = $this->validateUserInput($request);
        if (isset($validated_values['error'])) return $validated_values['errors_array'];

        $user_lat_long = $validated_values['user_lat_long'];
        $country = $validated_values['country'];

        $serviceable_area_settings = $this->settingsByKeys('tenant', ['serviceable_area'], null, null, 1, null)->toArray()['serviceable_area'];
        $countries = collect($serviceable_area_settings)->where('method', 'country')->toArray();

        if ($country) {
            $serviceable_area_status = $this->checkByCountry($countries, $country);
            if ($serviceable_area_status) return true;
        }

        foreach ($serviceable_area_settings as $setting) {

            if ($setting['method'] == 'radius') {
                $serviceable_area_status = $this->checkByRadius($setting, $user_lat_long);
                if ($serviceable_area_status) return true;
            }

            if ($setting['method'] == 'geofence') {
                $serviceable_area_status = $this->checkByGeofence($setting, $user_lat_long);
                if ($serviceable_area_status) return true;
            }
        }

        // Purpose: Return the message below if the user location falls out of the serviceable area.
        return "Out of serviceable area.";
    }

    public function returnGeofence($coordinates_array)
    {
        $geofence = new Polygon();
        foreach ($coordinates_array as $coordinates) {
            $coordinates = array_values($coordinates);
            $geofence->addPoint(new Coordinate($coordinates[0], $coordinates[1]));
        }
        return $geofence;
    }

    public function checkByCountry($countries_array_data, $user_lat_long)
    {
        foreach ($countries_array_data as $countries) {
            $countries_array = $countries['value'];
            $country_status = in_array($user_lat_long, $countries_array);
            if ($country_status) return true;
        }
        return false;
    }

    public function checkByRadius($setting, $user_lat_long)
    {
        $destination_location = $setting['value']['location'];
        $radius = $setting['value']['radius'];
        $scale = $setting['value']['scale'];

        $radius_in_metres = $this->getRadiusInMetres($scale, $radius);

        $user_lat_long = new Coordinate($user_lat_long[0], $user_lat_long[1]);
        $destination_location = new Coordinate($destination_location[0], $destination_location[1]);

        $distance = $user_lat_long->getDistance($destination_location, new Vincenty());

        if ($distance < $radius_in_metres) {
            return true;
        }

        if ($distance > $radius_in_metres) {
            return false;
        }
    }

    public function checkByGeofence($setting, $user_lat_long)
    {
        $geofence_coordinates = $setting['value'][0];
        $geofence = $this->returnGeofence($geofence_coordinates);

        $user_lat_long = new Coordinate($user_lat_long[0],$user_lat_long[1]);

        $user_point_inside = $geofence->contains($user_lat_long);

        if (!$user_point_inside) {
            return false;
        }

        return true;
    }

    public function getRadiusInMetres($scale, $radius)
    {
        if ($scale == 'kms') {
            $radius_in_metres =  $radius * 1000;
            return $radius_in_metres;
        }
        if ($scale == 'miles') {
            $radius_in_metres = $radius * 1609.34;
            return $radius_in_metres;
        }
    }

    public function validateUserInput($request)
    {
        $country_codes = $this->fetchCountries();
        $country_codes = collect($country_codes['data'])->pluck('code')->toArray();

        Validator::extend('country_exists', function($attribute, $value, $parameters) use ($country_codes) {
            $country_present = in_array($value, $country_codes);
            if (!$country_present) {
                return false;
            }

            return true;
        }, "Invalid country code(s)");

        Validator::extend('double', function($attribute, $value, $parameters) {

            $coordinates = $value;
            foreach ($coordinates as $coordinate) {
                if (gettype($coordinate) !== 'double') {
                    return false;
                }
            }

            return true;
        }, "Invalid coordinates");

        $validator = Validator::make($request->all(), [
            'country' => 'country_exists',
            'user_lat_long' => ["array","min:2","max:2"],
            'user_lat_long' => 'double'
        ]);

        if ($validator->fails()) {
            $validation_errors['error'] = true;
            $validation_errors['errors_array'] = $validator->errors()->toArray();
            return $validation_errors;
        }

        $validated_values = $validator->validate();

        return $validated_values;
    }
}
