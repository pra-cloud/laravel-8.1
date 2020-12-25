<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;
use Illuminate\Http\Request;

class DeliverySettingsController extends Controller
{
    use SettingsServiceTrait;

    public function updateDeliveryCalculations(Request $request)
    {
        try {

            $this->validate($request, [
                'tenant_id' => "required",
                "setting_value" => "required",
            ]);

            $setting_key = "tenant_delivery_calculation_method";
            $setting_tag = "tenant_delivery_settings";

            $response = $this->updateSetting(
                $setting_key,
                $request->post("setting_value"),
                $setting_tag,
                $request->post("tenant_id"),
                null,
            );

            return $this->processServiceResponse($response, "Delivery calculation method setting updated.");

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

            $setting_key = "tenant_delivery_calculation_method";
            $setting_tag = "tenant_delivery_settings";

            $response = $this->settingsByKey(
                $setting_key,
                $request->post("tenant_id"),
                null,
            );

            return $this->processServiceResponse($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateDeliveryFeeSource(Request $request)
    {
        try {

            $this->validate($request, [
                'tenant_id' => "required",
                "setting_value" => "required",
            ]);

            $setting_key = "tenant_delivery_fee_source";
            $setting_tag = "tenant_delivery_settings";
            $user_type = "tenant";

            $response = $this->updateSetting(
                $setting_key,
                $request->post("setting_value"),
                $setting_tag,
                $request->post("tenant_id"),
                null,
            );

            return $this->processServiceResponse($response, "Delivery fee source setting updated.");

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

            $setting_key = "tenant_delivery_fee_source";
            $setting_tag = "tenant_delivery_settings";
            $user_type = "tenant";

            $response = $this->settingsByKey(
                $setting_key,
                $request->post("tenant_id"),
                null,
            );

            return $this->processServiceResponse($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateFlatDeliveryFee(Request $request)
    {

        try {

            $this->validate($request, [
                'tenant_id' => "required",
                "setting_value" => "required",
            ]);

            $setting_key = "tenant_delivery_fee_flat";
            $setting_tag = "tenant_delivery_settings";
            $user_type = "tenant";

            $response = $this->updateSetting(
                $setting_key,
                $request->post("setting_value"),
                $setting_tag,
                $request->post("tenant_id"),
                null,
            );

            return $this->processServiceResponse($response, "Flat delivery fee setting updated.");

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

            $setting_key = "tenant_delivery_fee_flat";
            $setting_tag = "tenant_delivery_settings";
            $user_type = "tenant";

            $response = $this->settingsByKey(
                $setting_key,
                $request->post("tenant_id"),
                null,
            );

            return $this->processServiceResponse($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateCurrency(Request $request)
    {

    }
}
