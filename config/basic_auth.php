<?php

return [
    'enabled'      => env('BASIC_AUTH_ENABLED', false),
    'approved_ips' => explode(':', env('BASIC_AUTH_APPROVED_IPS', '')),
    'username'     => env('BASIC_AUTH_USERNAME', 'franca'),
    'password'     => env('BASIC_AUTH_PASSWORD', 'franca'),
];
