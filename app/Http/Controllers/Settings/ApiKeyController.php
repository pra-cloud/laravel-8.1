<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;
use Illuminate\Http\Request;
use App\Services\SettingsRepository;

class ApiKeyController extends Controller
{
    use SettingsServiceTrait;
    private $SETTINGS_REPOSITORY;

    function __construct(SettingsRepository $settings_repository)
    {
        $this->SETTINGS_REPOSITORY = $settings_repository;
    }

    public function updateGmapApiKey(Request $request)
    {
        try {
            $response = $this->SETTINGS_REPOSITORY->updateDeliveryCalculations($request->all());
            return $this->processServiceResponse($response, "API Key updated.");

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function fetchGmapApiKey(Request $request)
    {
        try {
            $response = $this->SETTINGS_REPOSITORY->fetchDeliveryCalculations($request->all());
            return $this->processServiceResponse($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

}
