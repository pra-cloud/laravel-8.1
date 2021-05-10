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

    public function __construct(SettingsRepository $settings_repository)
    {
        $this->SETTINGS_REPOSITORY = $settings_repository;
    }

    // Delivery Settings
    public function updateDeliveryCalculations(Request $request)
    {
        try {
            $response = $this->SETTINGS_REPOSITORY->updateDeliveryCalculations($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SETTINGS_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function fetchDeliveryCalculations(Request $request)
    {
        try {
            $response = $this->SETTINGS_REPOSITORY->fetchDeliveryCalculations($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SETTINGS_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function updateDeliveryFeeSource(Request $request)
    {
        try {
            $response = $this->SETTINGS_REPOSITORY->updateDeliveryFeeSource($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SETTINGS_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function fetchDeliveryFeeSource(Request $request)
    {
        try {
            $response = $this->SETTINGS_REPOSITORY->fetchDeliveryFeeSource($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SETTINGS_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function updateFlatDeliveryFee(Request $request)
    {
        try {
            $response = $this->SETTINGS_REPOSITORY->updateFlatDeliveryFee($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SETTINGS_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function fetchFlatDeliveryFee(Request $request)
    {
        try {
            $response = $this->SETTINGS_REPOSITORY->fetchFlatDeliveryFee($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SETTINGS_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function updateCurrency(Request $request)
    {
    }
}
