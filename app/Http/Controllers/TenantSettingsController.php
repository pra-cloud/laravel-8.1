<?php

namespace App\Http\Controllers;

use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;
use Illuminate\Http\Request;

class TenantSettingsController extends Controller
{
    use SettingsServiceTrait;


    public function updateDeliveryCalculations(Request $request)
    {


        try {

            $this->validate($request, [

                'tenant_id' => "required",
                "setting_value" => "required"

            ]);

            $setting_key="tenant_delivery_calculation_method";
            $setting_tag="tenant_delivery_settings";
            $user_type="tenant";

            $response = $this->updateSetting(

                $setting_key,
                $request->post("setting_value"),
                $request->post("tenant_id"),
                null,
                $user_type,
                $setting_tag
            );


            return response()->json($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }


    public function fetchDeliveryCalculations(Request $request)
    {


        try {

            $this->validate($request, [

                'tenant_id' => "required",

            ]);

            $setting_key="tenant_delivery_calculation_method";
            $setting_tag="tenant_delivery_settings";
            $user_type="tenant";

            $response = $this->settingsByKey(

                $setting_key,
                $request->post("tenant_id"),
                null,
                $user_type

            );


            return response()->json($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }



    public function updateDeliveryFeeSource(Request $request)
    {


        try {

            $this->validate($request, [

                'tenant_id' => "required",
                "setting_value" => "required"

            ]);

            $setting_key="tenant_delivery_fee_source";
            $setting_tag="tenant_delivery_settings";
            $user_type="tenant";

            $response = $this->updateSetting(

                $setting_key,
                $request->post("setting_value"),
                $request->post("tenant_id"),
                null,
                $user_type,
                $setting_tag
            );


            return response()->json($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }


    public function fetchDeliveryFeeSource(Request $request)
    {


        try {

            $this->validate($request, [

                'tenant_id' => "required",

            ]);

            $setting_key="tenant_delivery_fee_source";
            $setting_tag="tenant_delivery_settings";
            $user_type="tenant";

            $response = $this->settingsByKey(

                $setting_key,
                $request->post("tenant_id"),
                null,
                $user_type

            );


            return response()->json($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }



    public function updateFlatDeliveryFee(Request $request)
    {


        try {

            $this->validate($request, [

                'tenant_id' => "required",
                "setting_value" => "required"

            ]);

            $setting_key="tenant_delivery_fee_flat";
            $setting_tag="tenant_delivery_settings";
            $user_type="tenant";

            $response = $this->updateSetting(

                $setting_key,
                $request->post("setting_value"),
                $request->post("tenant_id"),
                null,
                $user_type,
                $setting_tag
            );


            return response()->json($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }


    public function fetchFlatDeliveryFee(Request $request)
    {


        try {

            $this->validate($request, [

                'tenant_id' => "required",

            ]);

            $setting_key="tenant_delivery_fee_flat";
            $setting_tag="tenant_delivery_settings";
            $user_type="tenant";

            $response = $this->settingsByKey(

                $setting_key,
                $request->post("tenant_id"),
                null,
                $user_type

            );


            return response()->json($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateCurrency(Request $request)
    {

    }
}
