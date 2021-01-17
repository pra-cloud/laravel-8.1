<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    use SettingsServiceTrait;

    public function updateGmapApiKey(Request $request)
    {
        try {

            $this->validate($request, [
                'tenant_id' => "required",
                "setting_value" => "required",
            ]);

            $setting_key = "gmap_api_key";
            $setting_tag = "api_key";

            $response = $this->updateSetting(
                $setting_key,
                $request->post("setting_value"),
                $setting_tag,
                $request->post("tenant_id"),
                null,
            );

            return $this->processServiceResponse($response, "API Key updated.");

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

}
