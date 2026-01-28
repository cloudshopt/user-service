<?php

return [
    'secret' => env('JWT_SECRET'),
    'ttl' => (int) env('JWT_TTL_SECONDS', 3600),
    'issuer' => env('JWT_ISSUER', 'cloudshopt-user-service'),
    'audience' => env('JWT_AUDIENCE', 'cloudshopt'),
];