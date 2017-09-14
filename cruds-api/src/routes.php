<?php
// Routes
use Slim\Views\PhpRenderer;

include_once __DIR__ . '/models/Usuario.php';
include_once __DIR__ . '/Payments/PagSeguro.php';
include_once __DIR__ . '/Requests/PagSeguro.php';
include_once __DIR__ . '/Notifications/PagSeguro.php';
include_once __DIR__ . '/Requests/PagSeguroNotification.php';
include_once __DIR__ . '/MakeRequest.php';

function moveUploadedFile($directory, $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);
    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

function sendWelcomeMail($email)
{
    $subject = 'Bem Vindo!';
    $message = 'Seja bem vindo ao Professor Ramayana!';
    return sendMail($email, $subject, $message);
}

function sendPaymentConfirmationEmail($usuario)
{
    $subject = 'Seu pagamento foi confirmado!';
    $message = 'Seu pagamento foi confirmado! Agora você pode acessar os videos do Professor Ramayana!';
    return sendMail($usuario->email, $subject, $message);
}


function sendMail($email, $subject, $message)
{
    $to = $email;
    $headers = 'From: contato@professorramayana.com.br' . "\r\n" .
        'Reply-To: contato@professorramayana.com.br' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    return mail($to, $subject, $message, $headers);
}

function getVideoIdFromYoutubeLink($video_link)
{
    $subject = $video_link;
    $url = parse_url($subject);
    parse_str($url['query'], $query);
    return $query['v'];
}

function downloadYoutubeThumb($directory, $url)
{
    $content = file_get_contents($url);
    $thumb_name = uniqid('yt-thumb', true) . '.jpg';
    $fp = fopen($directory . '/' . $thumb_name, "w");
    fwrite($fp, $content);
    fclose($fp);

    return $thumb_name;
}


$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("../templates");

$settings = $container->get('settings');

define("BASE_URL", $settings['base_url']);
define('FACEBOOK_APP_ID', $settings['facebook_app_id']);
define('FACEBOOK_APP_SECRET', $settings['facebook_app_secret']);

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
    $this->renderer->render($response, "/admin/login.phtml", ['base_url' => BASE_URL]);
});

$app->get('/admin/user_logout', function ($request, $response, $args) {
    unset($_SESSION['is_logged']);
    return $response->withRedirect(BASE_URL . '/admin');
});

$app->post('/admin/user_login', function ($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $email = $parsedBody['email'];
    $senha = $parsedBody['senha'];

    if ($email == 'professorramayana@gmail.com' &&
        $senha == 'adminramayana'
    ) {
        $_SESSION['is_logged'] = true;


        return $response->withRedirect(BASE_URL . '/admin/textos/list');
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

    $config_valor = Config::find(2);
    $pag_seguro->addProduct(1, 'Videos Ramayana', $config_valor->value, 1);


    //Request
    $responsePagSeguro = (new BrPayments\MakeRequest($pag_seguro_request))->post($pag_seguro, true);

    $xml = new \SimpleXMLElement((string)$responsePagSeguro);

    $url = $pag_seguro_request->getUrlFinal($xml->code, true);

    $this->renderer->render(
        $response,
        "/user/list_videos.phtml",
        [
            'xml' => $xml,
            'url' => $url
        ]
    );
});

$app->get('/test', function ($request, $response, $args) {
    $xmlStr = <<<XML
<transaction>
<date>2017-09-01T17:01:23.000-03:00</date>
<code>E80B8B45-B115-406F-B6E7-276A667AFFA9</code>
<reference>Ref-59a9bc879ced71.07887179</reference>
<type>1</type>
<status>3</status>
<lastEventDate>2017-09-01T17:09:33.000-03:00</lastEventDate>
<paymentMethod>
<type>1</type>
<code>101</code>
</paymentMethod>
<grossAmount>30.00</grossAmount>
<discountAmount>0.00</discountAmount>
<creditorFees>
<installmentFeeAmount>0.00</installmentFeeAmount>
<intermediationRateAmount>0.40</intermediationRateAmount>
<intermediationFeeAmount>1.20</intermediationFeeAmount>
</creditorFees>
<netAmount>28.40</netAmount>
<extraAmount>0.00</extraAmount>
<escrowEndDate>2017-10-01T17:09:33.000-03:00</escrowEndDate>
<installmentCount>1</installmentCount>
<itemCount>1</itemCount>
<items>
<item>
<id>1</id>
<description>Videos Ramayana</description>
<quantity>1</quantity>
<amount>30.00</amount>
</item>
</items>
<sender>
<name>Felipe Joazeiro</name>
<email>pinho_joazeiro@hotmail.com</email>
</sender>
<shipping>
<address>
<street>RUA AQUIDABA</street>
<number>1126</number>
<complement>Bloco 1 Apartamento 304</complement>
<district>Méier</district>
<city>RIO DE JANEIRO</city>
<state>RJ</state>
<country>BRA</country>
<postalCode>20720293</postalCode>
</address>
<type>1</type>
<cost>0.00</cost>
</shipping>
<primaryReceiver>
<publicKey>PUB73613C0F0F1B47B980D60F320CCB0DC7</publicKey>
</primaryReceiver>
</transaction>
XML;


    $xml = new SimpleXMLElement($xmlStr);
    echo $xml->lastEventDate . '<br />';

    $lastEvent = date($xml->lastEventDate);
    $lastEvent = strtotime("+3 months", strtotime($lastEvent)); // returns timestamp
    $access_expiration_date = date('Y-m-d', $lastEvent);
    echo $access_expiration_date . '<br />';
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


include_once 'routes/pagseguro_route.php';
include_once 'routes/site_routes.php';
include_once 'routes/textos_routes.php';
include_once 'routes/aulas_routes.php';
include_once 'routes/informes_routes.php';
include_once 'routes/sitios_routes.php';
include_once 'routes/videos_routes.php';
include_once 'routes/sobre_routes.php';
include_once 'routes/user_routes.php';
include_once 'routes/config_routes.php';
