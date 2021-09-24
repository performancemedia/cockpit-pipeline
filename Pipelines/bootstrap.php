<?php

$directories = [
    'Model',
    'Provider'
];

foreach ($directories as $directory) {
    spl_autoload_register(function ($class) use ($directory) {
        $class_path = __DIR__ . '/' . $directory . '/' . str_replace('\\', '/', $class) . '.php';

        if (file_exists($class_path)) {
            include_once($class_path);
        }
    });
}

// REST
if (COCKPIT_API_REQUEST) {
    $app->on('cockpit.rest.init', function ($routes) {
        $routes['pipelines'] = 'Pipelines\\Controller\\RestApi';
    });
}

// ADMIN
if (COCKPIT_ADMIN && !COCKPIT_API_REQUEST) {
    include_once(__DIR__ . '/admin.php');
}
