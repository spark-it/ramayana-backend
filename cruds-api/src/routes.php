<?php
// Routes
use Slim\Views\PhpRenderer;

include_once __DIR__ . '/models/Usuario.php';
include_once __DIR__ . '/Payments/PagSeguro.php';
include_once __DIR__ . '/Requests/PagSeguro.php';
include_once __DIR__ . '/MakeRequest.php';

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


$app->get('/admin', function ($request, $response, $args) {
    $this->renderer->render($response, "/login.phtml", ['base_url' => BASE_URL]);
});

$app->get('/user_logout', function ($request, $response, $args) {
    unset($_SESSION['is_logged']);
    return $response->withRedirect(BASE_URL . '/admin');
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
        return $response->withRedirect(BASE_URL . '/admin');
    }
});


//Teste


$app->get('/pagseguro_venda', function ($request, $response) {

    $access = [
        'email' => 'professorramayana@gmail.com',
        'token' => '6C461B67BD034EE8A9B1BA64E40F744D',
        'currency' => 'BRL',
        'reference' => 'REF1234'
    ];

    $pag_seguro = new BrPayments\Payments\PagSeguro($access);
    $pag_seguro_request = new BrPayments\Requests\PagSeguro;


//name, areacode, phone, email
    $pag_seguro->customer('', '', '', 'c30724139981788620484@sandbox.pagseguro.com.br');
//    $pag_seguro->shipping(
//        1,
//        'Av. PagSeguro',
//        99,
//        '99o andar',
//        'Jardim Internet',
//        99999999,
//        'Cidade Exemplo',
//        'SP',
//        'ATA'
//    );


    $pag_seguro->shipping(
        1,
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        'ATA'
    );



    $pag_seguro->addProduct(1, 'Videos Ramayana', 40.00, 1);


    //Request
    $responsePagSeguro = (new BrPayments\MakeRequest($pag_seguro_request))->post($pag_seguro, true);

    $xml = new \SimpleXMLElement((string)$responsePagSeguro);

    $url = $pag_seguro_request->getUrlFinal($xml->code, true);

    $this->renderer->render($response, "/user/list_videos.phtml",
        [
            'xml' => $xml,
            'url' => $url
        ]);
});

$app->get('/pagseguro_redir', function ($request, $response, $args) {
    $PagSeguro = new PagSeguro();

    if (isset($args['transaction_id'])) {
        $pagamento = $PagSeguro->getStatusByReference($args['id_fat']);
        $pagamento->codigo_pagseguro = $args['transaction_id'];
        if ($pagamento->status == 3 || $pagamento->status == 4) {
            echo 'pagamento confirmado</br>';
            var_dump($pagamento);
        } else {
            echo $PagSeguro->getStatusText($PagSeguro->status);
        }
    } else {
        echo 'nenhum parametro enviado';
        var_dump($args);
    }
});


$app->post('/pagseguro_notificacao', function ($request, $response) {
    $parsedBody = $request->getParsedBody();
    if (isset($parsedBody['notificationType'])) {
        $notificationType = $parsedBody['notificationType'];
        if ($notificationType == 'transaction') {
            $PagSeguro = new PagSeguro();
            $resposta = $PagSeguro->executeNotification($parsedBody);
            if ($resposta->status == 3 || $resposta->status == 4) {
                echo 'pagamento confirmado</br>';
                var_dump($resposta);
            } else {
                echo $PagSeguro->getStatusText($PagSeguro->status);
            }
        }
    } else {
        echo 'nenhum parametro enviado';
        var_dump($parsedBody);
    }
});


$app->get('/pagseguro_getstatus/{reference}', function ($request, $response, $args) {
    if (isset($args['reference'])) {
        $pagSeguro = new PagSeguro();
        echo 'foi';
        $p = $pagSeguro->getStatusByReference($args['reference']);
        echo 'foi';
        echo $pagSeguro->getStatusText($p->status);
        echo 'foi';
    } else {
        echo 'Parametro não informado';
    }
});


include_once 'routes/site_routes.php';
include_once 'routes/textos_routes.php';
include_once 'routes/aulas_routes.php';
include_once 'routes/informes_routes.php';
include_once 'routes/sitios_routes.php';
include_once 'routes/videos_routes.php';
include_once 'routes/sobre_routes.php';
include_once 'routes/user_routes.php';