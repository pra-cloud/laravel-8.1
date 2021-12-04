<?php

namespace App\Http\Controllers;

use App\Modules\Billing\Billing;
use Illuminate\Http\Request;

class WebhookBillingProviderController extends Controller
{
    public function handle(Request $request, $provider)
    {
        $billing_provider = Billing::init($provider);
        $billing_provider->processWebhook($request->all());
    }
}
