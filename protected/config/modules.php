<?php

return array(
    'modules' => array(
        'wow',
        'user',
        'rights',
        'gallery',
        'gii' => array(
            'class'     => 'system.gii.GiiModule',
            'password'  => '123',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array(
                'bootstrap.gii', // since 0.9.1
            ),
        ),
    )
);
