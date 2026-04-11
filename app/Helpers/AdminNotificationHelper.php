<?php

namespace App\Helpers;

use App\Models\Notification;

class AdminNotificationHelper
{
    public static function create(
        string $title,
        ?string $message = null,
        ?string $type = null,
        ?string $targetUrl = null,
        array $data = []
    ): void {
        Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'target_url' => $targetUrl,
            'data' => $data,
        ]);
    }
}