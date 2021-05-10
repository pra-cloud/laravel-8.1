<?php
namespace App\Repositories;

use Exception;
use Validator;

class SettingsRepository extends BaseRepository
{
    protected $account_type = 'tenant';

    // Delivery Settings
    public function updateDeliveryCalculations(array $params)
    {
        $validator = Validator::make($params, [
            'tenant_id' => "required",
            "setting_value" => "required",
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }
        
        $settings["tenant_delivery_calculation_method"] = $params['setting_value'];

        $response = $this->updateSetting(
            'tenant',
            $settings,
            $params['tenant_id'],
            null,
        );

        return $response;
    }

    public function fetchDeliveryCalculations(array $params)
    {
        $validator = Validator::make($params, [
            'tenant_id' => "required"
        ]);
        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $setting_key = 'tenant_delivery_calculation_method';
        $setting_tag = "tenant_delivery_settings";
        $account_type = $this->account_type;
        
        $response = $this->settingsByKey(
            $account_type,
            null,
            $setting_key,
            $params['tenant_id'],
            null,
        );

        return $response;
    }

    public function updateDeliveryFeeSource($params)
    {
        $validator = Validator::make($params, [
            "tenant_id" => "required",
            "setting_value" => "required"
        ]);
        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $settings["tenant_delivery_fee_source"] = $params['setting_value'];
        $response = $this->updateSetting(
            $settings,
            $params['tenant_id'],
            null,
        );

        return $response;
    }

    public function fetchDeliveryFeeSource($params)
    {
        $validator = Validator::make($params, [
            'tenant_id' => "required"
        ]);
        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
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
        $validator = Validator::make($params, [
            'tenant_id' => "required",
            "setting_value" => "required"
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $settings["tenant_delivery_fee_flat"] = $params['setting_value'];
        $response = $this->updateSetting(
            $settings,
            $params['tenant_id'],
            null,
        );

        return $response;
    }

    public function fetchFlatDeliveryFee($params)
    {
        $validator = Validator::make($params, [
            'tenant_id' => "required"
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
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
        $validator = Validator::make($params, [
            'tenant_id' => "required",
            "setting_value" => "required",
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
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
        $validator = Validator::make($params, [
            'tenant_id' => "required"
        ]);
        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
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
