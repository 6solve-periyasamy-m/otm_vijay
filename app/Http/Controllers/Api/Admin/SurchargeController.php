<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\InvalidDataException;
use App\Http\Controllers\ApiController;
use App\Http\Gateways\StripeGateway;
use App\Http\Requests\SetSurchargeRequest;
use Illuminate\Http\JsonResponse;

class SurchargeController extends ApiController
{
    public function setStripeSurcharge(SetSurchargeRequest $request): JsonResponse
    {
        try {
            StripeGateway::setStripeSurcharge($request->currency, $request->surcharge);
            return response()->json(['success' => true, 'message' => 'Surcharge successfully set'], 200);
        } catch (InvalidDataException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}