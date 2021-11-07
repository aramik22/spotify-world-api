<?php
$base = __DIR__ . '/../src/';

$folders = [
    'model',
    'route'
];

foreach ($folders as $f) {
    foreach (glob($base . "$f/*.php") as $filename) {
        require $filename;
    }
}
