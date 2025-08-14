<?php

return [

    'paths' => ['*', 'sanctum/csrf-cookie'],


    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://app3.webnew.info.vn'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
