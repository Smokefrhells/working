<?php

return [
    'users.index' => [
        'uri' => 'users',
        'method' => 'get',
        'controller' => '\App\Controllers\UsersController@index',
    ],
    'home' => [
        'uri' => '',
        'method' => 'get',
        'controller' => '\App\Controllers\HomeController@index',
    ],
];