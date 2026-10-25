<?php
// Shop settings. Override any of these in your .env file.
return [
    'name'            => env('SHOP_NAME', 'Marginalia Books'),
    'currency'        => env('SHOP_CURRENCY', '₹'),
    'stripe_currency' => env('STRIPE_CURRENCY', 'inr'),
    'stripe_secret'   => env('STRIPE_SECRET'),
    'email'           => env('SHOP_EMAIL', 'hello@marginalia.test'),
    'phone'           => env('SHOP_PHONE', '+91 0000000000'),
    'address'         => env('SHOP_ADDRESS', '123 Library Lane,  City'),
    'lat'             => env('SHOP_LAT', 51.5074),
    'lng'             => env('SHOP_LNG', -0.1278),
];
