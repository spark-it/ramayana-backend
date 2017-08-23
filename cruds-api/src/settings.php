<?php

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$base = 'http://cruds-api.dev:8888';
if (!strpos($actual_link,'dev')) {
    // $base = 'http://mayckxavier.com/projetos/ramayana-backend/public';
    $base = 'http://18.220.218.65';
}



return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // // Monolog settings
        // 'logger' => [
        //     'name' => 'slim-app',
        //     'path' => __DIR__ . '/../logs/app.log',
        //     'level' => \Monolog\Logger::DEBUG,
        // ],

        'base_url' => $base,
        'upload_dir' => __DIR__ . '/../public/uploads',
    ],
];
