<?php

require 'vendor/autoload.php';
require_once __DIR__ . '/bootstrap/database.php';
$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'outputBuffering' => false,
        'addContentLengthHeader' => false,
    ],
];
$app = new \Slim\App($config);

require 'src/routes/spotify_routes.php';
require __DIR__ . '/src/loader.php';

$app->run();
