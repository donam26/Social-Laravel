<?php

return [
    'paths' => ['api/*', 'broadcasting/auth'],
    'allowed_methods' => ['GET', 'POST'],
    'allowed_origins' => ['http://localhost:3000'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
