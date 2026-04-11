<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZenopayService
{
    protected $apiKey;
    protected $baseUrl;
    protected $callbackUrl;

    public function __construct()
    {
        $this->apiKey = config('services.zenopay.key');
        $this->baseUrl = config('services.zenopay.base_url');
        $this->callbackUrl = config('services.zenopay.callback_url');
    }

    public function stkPush($phone, $amount, $orderId)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->baseUrl . '/payments/mobile_money_tanzania', [
            "order_id"     => $orderId,
            "buyer_email"  => "customer@email.com",
            "buyer_name"   => "Customer Name",
            "buyer_phone"  => $phone,
            "amount"       => $amount,
            "callback_url" => $this->callbackUrl,
        ])->json();
    }
}
