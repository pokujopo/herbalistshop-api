<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;
use InvalidArgumentException;

class PaymentManager
{
    public function initiate(string $provider, array $payload): array
    {
        return match ($provider) {
            'azampay' => app(AzamPayService::class)->initiate($payload),
            'cash' => [
                'status' => 'pending',
                'message' => 'Cash on delivery selected.',
            ],
            default => throw new InvalidArgumentException("Unsupported provider: {$provider}"),
        };
    }

    public function handleWebhook(string $provider, Request $request)
    {
        return match ($provider) {
            'azampay' => app(AzamPayService::class)->handleWebhook($request),
            default => throw new InvalidArgumentException("Unsupported provider: {$provider}"),
        };
    }
}
