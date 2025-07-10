<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | Set trusted proxy IP addresses. Both IPv4 and IPv6 addresses are
    | supported, along with CIDR notation. The "*" character is syntactic
    | sugar within TrustedProxy to trust any proxy that connects
    | directly to your server, a requirement when you cannot know the address
    | of your proxy (e.g. if using Render, ELB, etc).
    |
    */

    'proxies' => env('TRUSTED_PROXIES', '*'),

    /*
    |--------------------------------------------------------------------------
    | Trusted Headers
    |--------------------------------------------------------------------------
    |
    | Headers that should be used to determine IP addresses and other
    | details about the request. These are activated when you trust
    | specific proxies above, or trust all proxies with "*".
    |
    */

    'headers' => [
        'X-Forwarded-For',
        'X-Forwarded-Host',
        'X-Forwarded-Port',
        'X-Forwarded-Proto',
        'X-Forwarded-Prefix',
    ],
];
