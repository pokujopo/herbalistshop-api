<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request, $provider, PaymentManager $paymentManager)
    {
        return $paymentManager->handleWebhook($provider, $request);
    }
}
