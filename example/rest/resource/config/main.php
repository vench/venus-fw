<?php

 
return [
    /**
     * 
     */
    'controllerPaths'   => [
        '\app\controller\rest',
        '\app\controller'
    ],
    
    /**
     * 
     */
    'routePathActions'  => [
        'user/all'    => ['method' => 'GET', 'path' => '/user',],
        'user/one'    => ['method' => 'GET', 'path' => '/user/<id:\d+>',],
        'user/add'    => ['method' => 'POST', 'path' => '/user',],
        'user/update' => ['method' => 'PUT', 'path' => '/user/<id:\d+>',],
        'user/delete' => ['method' => 'DELETE', 'path' => '/user/<id:\d+>',],
    ],
];

