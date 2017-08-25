<?php

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$base = 'http://cruds-api.dev:8888';
if (!strpos($actual_link,'dev')) {
    $base = 'http://professorramayana.com';
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
        'facebook_app_id' => '1546114922113473',
        'facebook_app_secret' => '99485e0d80b47066811e201f4102450c'
    ],
];
