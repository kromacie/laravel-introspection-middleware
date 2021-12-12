<?php

return [
    'default' => 'auth_server',

    'access_token_repository' => Kromacie\IntrospectionMiddleware\Repositories\CacheAccessTokenRepository::class,

    'servers' => [

        'auth_server' => [
            'client_id' => env('AUTH_SERVER_INTROSPECT_CLIENT_ID'),
            'client_secret' => env('AUTH_SERVER_INTROSPECT_CLIENT_SECRET'),
            'access_token_url' => env('AUTH_SERVER_INTROSPECT_ACCESS_TOKEN_URL'),
            'introspect_token_url' => env('AUTH_SERVER_INTROSPECT_ACCESS_TOKEN_URL'),
        ],

    ],
];
