<?php
namespace App\Repositories;

use Exception;
use Validator;

class SettingsRepository extends BaseRepository
{
    // Delivery Settings
    public function updateDeliveryCalculations(array $params)
    {
        $validate = Validator::make($params, [
            'tenant_id' => "required",
            "setting_value" => "required",
        ]);

        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $setting_key = "tenant_delivery_calculation_method";
        $setting_tag = "tenant_delivery_settings";

        $response = $this->updateSetting(
            $setting_key,
            $params["setting_value"],
            $setting_tag,
            $params["tenant_id"],
            null,
        );
        return $response;
    }

    public function fetchDeliveryCalculations($params)
    {
        $validate = Validator::make($params, [
            'tenant_id' => "required"
        ]);
        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $setting_key = "tenant_delivery_calculation_method";
        $setting_tag = "tenant_delivery_settings";

        $response = $this->settingsByKey(
            $setting_key,
            $params['tenant_id'],
            null,
        );

        return $response;
    }

    public function updateDeliveryFeeSource($params)
    {
        $validate = Validator::make($params, [
            'tenant_id' => "required",
            "setting_value" => "required"
        ]);
        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $setting_key = "tenant_delivery_fee_source";
        $setting_tag = "tenant_delivery_settings";
        $user_type = "tenant";

        $response = $this->updateSetting(
            $setting_key,
            $params['setting_value'],
            $setting_tag,
            $params['tenant_id'],
            null,
        );
        return $response;
    }

    public function fetchDeliveryFeeSource($params)
    {
        $validate = Validator::make($params, [
            'tenant_id' => "required"
        ]);
        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $setting_key = "tenant_delivery_fee_source";
        $setting_tag = "tenant_delivery_settings";
        $user_type = "tenant";

        $response = $this->settingsByKey(
            $setting_key,
            $params['tenant_id'],
            null,
        );
        return $response;
    }

    public function updateFlatDeliveryFee($params)
    {
        $validate = Validator::make($params, [
            'tenant_id' => "required",
            "setting_value" => "required"
        ]);
        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $setting_key = "tenant_delivery_fee_flat";
        $setting_tag = "tenant_delivery_settings";
        $user_type = "tenant";

        $response = $this->updateSetting(
            $setting_key,
            $params['setting_value'],
            $setting_tag,
            $params['tenant_id'],
            null,
        );
        return $response;
    }

    public function fetchFlatDeliveryFee($params)
    {
        $validate = Validator::make($params, [
            'tenant_id' => "required"
        ]);
        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $setting_key = "tenant_delivery_fee_flat";
        $setting_tag = "tenant_delivery_settings";
        $user_type = "tenant";

        $response = $this->settingsByKey(
            $setting_key,
            $params['tenant_id'],
            null,
        );
        return $response;
    }

    // API Settings
    public function updateGmapApiKey(array $params)
    {
        $validate = Validator::make($params, [
            'tenant_id' => "required",
            "setting_value" => "required",
        ]);

        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $settings["gmap_api_key"] = $params['setting_value'];

        $response = $this->updateSetting(
            $settings,
            $params['tenant_id'],
            null,
        );

        return $response;
    }

    public function fetchGmapApiKey($params)
    {
        $validate = Validator::make($params, [
            'tenant_id' => "required"
        ]);
        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $setting_key = "gmap_api_key";
        $setting_tag = "api_key";
        $user_type = "tenant";

        $response = $this->settingsByKey(
            $setting_key,
            $params['tenant_id'],
            null,
        );

        return $response;
    }
}
