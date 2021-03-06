<?php

declare( strict_types=1 );

return [
    /*
     * The hostname(s) this Laravel app is listening to.
     */
    'host'                  => [ 'carranker.com' ],

    /*
     * The location of the file containing the administrative password.
     */
    'administrative_secret' => '/etc/varnish/secret',

    /*
     * The port where the administrative tasks may be sent to.
     */
    'administrative_port'   => 6082,

    /*
     * The default amount of minutes that content rendered using the `CacheWithVarnish`
     * middleware should be cached.
     */
    'cache_time_in_minutes' => 5,

    /*
     * The name of the header that triggers Varnish to cache the response.
     */
    'cacheable_header_name' => 'X-Cacheable',
];
