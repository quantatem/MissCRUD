<?php

return [
    'database' => [
        'name' => 'dbname',
        'username' => 'root',
        'password' => '',
        'connection' => 'mysql:host=127.0.0.1;port=3306',
        'tablename' => 'thyForm',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
        ]
    ]
];