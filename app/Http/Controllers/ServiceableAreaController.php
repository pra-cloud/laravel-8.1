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
use App\Rules\CountryExists;
use App\Exceptions\ExceptionWithArray;
use App\Mixins\ValidationMixins;

class ServiceableAreaController extends Controller
{
    use ApiResponseTrait;
    use SettingsServiceTrait;
    use HelpersServiceTrait;
    use ValidationMixins;

    public function checkIfPresentOrnot(Request $request)
    {
        try {

            $area = $this->check($request);

            if (!$area['is_serviceable']) {
                return $this->errorResponse("Tenant not serviceable in your area.", $area);
            }

            return $this->successResponse("Tenant Serviceable", $area);
        } catch (ExceptionWithArray $exception) {

            $errors = $exception->getArrayData();
            return $this->errorResponse("Something went wrong.", $errors);

        }
    }

    public function check(Request $request) // : array $area
    {
        $validated = $this->validateUserInput($request);

        $user_lat_long = $validated['user_lat_long'];

        $serviceable_area_settings = $this->settingsByKeys('tenant', ['serviceable_area'], null, null, 1, null);

        // If the serviceable_area settings are not set, the tenant is serviceable globally, always return true.
        if ($serviceable_area_settings == false) {
            $area['is_serviceable'] = true;
            return $area;
        }

        $serviceable_area_settings = $serviceable_area_settings->toArray()['serviceable_area'];

        $countries = collect($serviceable_area_settings)->where('method', 'country')->toArray();

        // Tenant is serviceable in the requested country
        if (isset($country)) {
            $area = $this->checkByCountry($countries, $validated['country']);
            if ($area['is_serviceable']) {
                return $area;
            }
        }


        foreach ($serviceable_area_settings as $setting) {

            if ($setting['method'] == 'radius') {
                $serviceable_area_status = $this->checkByRadius($setting, $user_lat_long);
                if ((isset($serviceable_area_status['is_serviceable']) && $serviceable_area_status['is_serviceable'])) {
                    return $serviceable_area_status;
                }
            }

            if ($setting['method'] == 'geofence') {
                $serviceable_area_status = $this->checkByGeofence($setting, $user_lat_long);
                if (isset($serviceable_area_status['is_serviceable']) && $serviceable_area_status['is_serviceable']) {
                    return $serviceable_area_status;
                }
            }
        }

        // Purpose: Return the message below if the user location falls out of the serviceable area.
        $area['is_serviceable'] = false;
        return $area;
    }

    public function checkByCountry($countries_array_data, $user_input_country) // : array $area
    {
        foreach ($countries_array_data as $countries) {
            $countries_array = $countries['value'];
            $country_status = in_array($user_input_country, $countries_array);
            if ($country_status) {
                $area['name'] = $countries['name'];
                $area['group_ids'] = $countries['group_ids'] ?? [];
                $area['is_serviceable'] = true;
                return $area;
            }
        }
        $area['is_serviceable'] = false;
        return $area;
    }

    // Inside a foreach loop
    public function checkByRadius($setting, $user_lat_long) // : array $area
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

    // Inside a foreach loop
    public function checkByGeofence($setting, $user_lat_long) // : array $area
    {
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
}
