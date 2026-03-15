<?php
    
    define('PATH', realpath('.'));
    define('SUBFOLDER', false);
    define('URL', 'https://yourwebsite.com');
    define('DINAMICLISANCE', 'GLYCON-THAZV-KGEYP-RMYOL');
    
    ini_set('display_errors', 0);
    date_default_timezone_set('Europe/Istanbul');
    
    return [
      'db' => [
        'name'    =>  '',
        'host'    =>  'localhost',
        'user'    =>  '',
        'pass'    =>  '',
        'charset' =>  'utf8mb4' 
      ]
    ];
    