<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SettingsRepository;

class ApiKeyController extends Controller
{
    private $SETTINGS_REPOSITORY;

    function __construct(SettingsRepository $settings_repository)
    {
        $this->SETTINGS_REPOSITORY = $settings_repository;
    }

    public function updateGmapApiKey(Request $request)
    {
        try {
            $response = $this->SETTINGS_REPOSITORY->updateGmapApiKey($request->all());
            return $this->processServiceResponse($response, "API Key updated.");
        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function fetchGmapApiKey(Request $request)
    {
        try {
            $response = $this->SETTINGS_REPOSITORY->fetchGmapApiKey($request->all());
            return $this->processServiceResponse($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

}
