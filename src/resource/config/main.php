<?php


setlocale(LC_MONETARY, 'ru_RU');

return [
    'defaultPage'   => 'site',
    'isLocal'       => true,
    
    
    'db'    =>  [
        'dsn'       => 'mysql:dbname=testdb;host=127.0.0.1',
        'user'      => 'root',
        'password'  => 'admin',
    ],
    
    /**
     * 
     */
    'controllerPaths'   => [
        'sale\controller'
    ],
]; 
