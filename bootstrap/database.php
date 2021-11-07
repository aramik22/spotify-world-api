<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$config = [
    'driver'    => 'sqlite',
    'host'      => 'localhost',
    'database'  => __DIR__.'/../spotify_development_db.sqlite3',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];
$capsule = new Capsule;
$capsule->addConnection(array_merge($config, [
    'charset'   =>  'utf8',
    'collation' =>  'utf8_unicode_ci'
]));

$capsule->bootEloquent();
$capsule->setAsGlobal();
