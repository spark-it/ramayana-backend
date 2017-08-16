<?php
// Routes
use Slim\Views\PhpRenderer;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;


include __DIR__ . '/models/Usuario.php';

function moveUploadedFile($directory, $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("../templates");

$settings = $container->get('settings');

define("BASE_URL", $settings['base_url']);

function check_logged($response)
{
    if (!isset($_SESSION['is_logged'])
        || is_null($_SESSION['is_logged'])
        || $_SESSION['is_logged'] != true
    ) {
        header('Location: ' . BASE_URL . '/');
        return $response->withRedirect(BASE_URL);
    }
}


$app->get('/', function ($request, $response, $args) {
    $this->renderer->render($response, "/login.phtml", ['base_url' => BASE_URL]);
});


$app->get('/user_logout', function ($request, $response, $args) {
    unset($_SESSION['is_logged']);
    return $response->withRedirect(BASE_URL);
});


$app->post('/user_login', function ($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $email = $parsedBody['email'];
    $senha = $parsedBody['senha'];

    if ($email == 'professorramayana@gmail.com' &&
        $senha == 'adminramayana'
    ) {
        $_SESSION['is_logged'] = true;


        return $response->withRedirect(BASE_URL . '/forms/textos/list');
    } else {
        return $response->withRedirect(BASE_URL);
    }
});


$app->get('/user_login/{access_token}', function ($request, $response, $args) {
    $access_token = $args['access_token'];


    $fb = new Facebook([
        'app_id' => '1546114922113473',
        'app_secret' => '99485e0d80b47066811e201f4102450c',
        'default_graph_version' => 'v2.7',
    ]);

    try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $fb->get('/me?fields=id,first_name,last_name', $access_token);
        $user = $response->getGraphUser();
        $user = Usuario::create([
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'facebook_id' => $user['id']
        ]);

        $_SESSION['user'] = $user;
        header('Location: ' . BASE_URL . '/forms/textos/list');

    } catch (FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
});


include 'routes/textos_routes.php';
include 'routes/aulas_routes.php';
include 'routes/informes_routes.php';
include 'routes/sitios_routes.php';
include 'routes/videos_routes.php';
include 'routes/sobre_routes.php';
include 'routes/user_routes.php';


