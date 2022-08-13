<?php

return [
    'client_id' => env('ZOOM_CLIENT_ID'),
    'client_secret' => env('ZOOM_CLIENT_SECRET'),
    'redirect_uri' => env('ZOOM_REDIRECT_URI'),
    'api_base_url' => env('API_BASE_URL'),
    'auth_base_url' => env('AUTH_BASE_URL'),
    'credential_path' => env('CREDENTIAL_PATH')

    // 'token_life' => 60 * 60 * 24 * 7, // In seconds, default 1 week
    // 'authentication_method' => 'jwt', // Only jwt compatible at present but will add OAuth2
    // 'max_api_calls_per_request' => '5' // how many times can we hit the api to return results for an all() request

];