<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URL'),
    ],
    
    'zenopay' => [
        'base_url' => env('ZENOPAY_BASE_URL'),
        'api_key' => env('ZENOPAY_API_KEY'),
        'callback_url' => env('ZENOPAY_CALLBACK_URL'),
    ],
    
    'azampay' => [
    'base_url' => env('AZAMPAY_BASE_URL'),
    'client_id' => env('AZAMPAY_CLIENT_ID'),
    'client_secret' => "H2FZA7BCoKQtlIdakoCvPqrowvJP3Y7S1QQqpgoyu2CJ582ijkbC+YTRsjwCqDs5iaud3MqbY7nRKR+I1RL09uSWuXl5T45JUonrCqNrMihPssxy//3htDeXzRl7Nz8yb/liJjgopE+Os7ONQSwswkqusB6LQDRpwJfHbYKSHhNEb0UqMYVqoqz+mii0GkXdzYuxLJKbKDSorkhhpwBojC/K+NsiWMiB35ODzbvaD42YW1oY5APwHVnsfIPlwD+Q5sqQO57bBnX21+ET9k846uQWUVeWOEUXPtzBcMabuuQglI+NBAsePGbbhY5qAohrGhxc+pdwhyQrQcQCFtZGBxR5rj43yQvZENBD+hWjEFTy6s7tsfzwNgRw0nduQFFo7gkknG89A60gfQ5Q6BkvZa2czkuv+MuQiJ4sr7+pbS3SLVIk4idsKnVydwlrVMPyUH/iQqbDonl6nYkC10gUkH/7wb3N0y2ElDnJQcPX3zxdziCnam4nNuDrsFhfEMvW3qcoPOM30aTEqcnXDvcoRMs5QyoXQXFS8Xa25tuyVPC0Kzz5BTEMb2OBZmuyYUYzScPYEUfoON7gT0B0P0ToZhfhVtqXzZ2I52BhYMoy4XAVILR+sEWOtIo8Gz+bgedL3DehgpmZ6oh1Ty3YuqU6elthWy/E0zTfm6XnUGsvtK8=",
    'app_name' => env('AZAMPAY_APP_NAME'),
],

];
