<?php

use App\Main;

$paths = [
    'App',
    'App\Services'
];

foreach ($paths as $path) {
    $files = glob($path.'/*.php');

    foreach ($files as $file) {
        require_once($file);
    }
}


$main = new Main();
$main->start();
