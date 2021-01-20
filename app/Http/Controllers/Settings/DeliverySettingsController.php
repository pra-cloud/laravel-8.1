<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;
use Illuminate\Http\Request;
use App\Repositories;
use App\Repositories\SettingsRepository;
use App\Tenant;

class DeliverySettingsController extends Controller
{
    use SettingsServiceTrait;
    private $SETTINGS_REPOSITORY;

    function __construct(SettingsRepository $settings_repository)
    {
        $this->SETTINGS_REPOSITORY = $settings_repository;
    }

    // Delivery Settings
    public function updateDeliveryCalculations(Request $request)
    {
        try {
            
            $response = $this->SETTINGS_REPOSITORY->updateDeliveryCalculations($request->all());
            return $this->processServiceResponse($response, "Delivery calculation method setting updated.");

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function fetchDeliveryCalculations(Request $request)
    {
        try {

            $response = $this->SETTINGS_REPOSITORY->fetchDeliveryCalculations($request->all());
            return $this->processServiceResponse($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateDeliveryFeeSource(Request $request)
    {
        try {

            $response = $this->SETTINGS_REPOSITORY->updateDeliveryCalculations($request->all());
            return $this->processServiceResponse($response, "Delivery fee source setting updated.");

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function fetchDeliveryFeeSource(Request $request)
    {
        try {

            $response = $this->SETTINGS_REPOSITORY->fetchDeliveryFeeSource($request->all());
            return $this->processServiceResponse($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateFlatDeliveryFee(Request $request)
    {
        try {

            $response = $this->SETTINGS_REPOSITORY->updateFlatDeliveryFee($request->all());
            return $this->processServiceResponse($response, "Flat delivery fee setting updated.");

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function fetchFlatDeliveryFee(Request $request)
    {
        try {

            $response = $this->SETTINGS_REPOSITORY->fetchDeliveryFeeSource($request->all());
            return $this->processServiceResponse($response);

        } catch (\Exception $e) {

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateCurrency(Request $request)
    {


    }
}
