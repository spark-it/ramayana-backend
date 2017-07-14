<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Database information
$settings = array(
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'cruds_api',
    'username' => 'root',
    'password' => 'senha',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
);


// $app->get('/', function () use ($app) {
//     $users = \User::all();
//     echo $users->toJson();
// });

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';


//$container = new Illuminate\Container\Container;
//$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory($container);
//$conn = $connFactory->make($settings);
//$resolver = new \Illuminate\Database\ConnectionResolver();
//$resolver->addConnection('default', $conn);
//$resolver->setDefaultConnection('default');
//\Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
