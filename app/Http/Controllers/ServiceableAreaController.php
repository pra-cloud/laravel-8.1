<?php

namespace App\Http\Controllers;

use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;
use Hyperzod\HyperzodServiceFunctions\Traits\HelpersServiceTrait;
use Illuminate\Http\Request;
use App\Exceptions\ExceptionWithArray;
use App\Mixins\ValidationMixins;
use App\Repositories\ServiceableAreaRepository;

class ServiceableAreaController extends Controller
{
    use SettingsServiceTrait;
    use HelpersServiceTrait;
    use ValidationMixins;
    use ServiceableAreaRepository;

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
        // Validate the incoming request
        $validated = $this->validateUserInput($request);

        // Collect crucial data
        $lat_long = $validated['user_lat_long'];

        $settings = $this->getSettings();

        // If the serviceable_area settings are not set, the tenant is serviceable globally, always return true.
        if ($settings == false) {
            $area['is_serviceable'] = true;
            return $area;
        }

        // Tenant is serviceable in the requested country
        if (isset($validated['country'])) {
            $countries = collect($settings)->where('method', 'country')->toArray();
            $area = $this->checkByCountry($countries, $validated['country']);
            if ($area['is_serviceable']) {
                return $area;
            }
        }


        foreach ($settings as $setting) {

            if ($setting['method'] == 'radius') {
                $serviceable_area_status = $this->checkByRadius($setting, $lat_long);
                if ((isset($serviceable_area_status['is_serviceable']) && $serviceable_area_status['is_serviceable'])) {
                    return $serviceable_area_status;
                }
            }

            if ($setting['method'] == 'geofence') {
                $serviceable_area_status = $this->checkByGeofence($setting, $lat_long);
                if (isset($serviceable_area_status['is_serviceable']) && $serviceable_area_status['is_serviceable']) {
                    return $serviceable_area_status;
                }
            }
        }

        // Purpose: Return the message below if the user location falls out of the serviceable area.
        $area['is_serviceable'] = false;
        return $area;
    }
}
