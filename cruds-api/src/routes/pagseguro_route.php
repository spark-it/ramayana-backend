<?php

use \src\models\Transacao;

include_once __DIR__ . '/../models/Usuario.php';
include_once __DIR__ . '/../models/Transacao.php';
include_once __DIR__ . '/../Payments/PagSeguro.php';
include_once __DIR__ . '/../Requests/PagSeguro.php';
include_once __DIR__ . '/../Requests/PagSeguroNotification.php';
include_once __DIR__ . '/../Notifications/PagSeguro.php';
include_once __DIR__ . '/../MakeRequest.php';

$app->post('/pagseguro/payment_notification', function ($request, $response) {
    $parsedBody = $request->getParsedBody();

    if (isset($parsedBody['notificationType'])) {
        $notificationType = $parsedBody['notificationType'];
        if ($notificationType == 'transaction') {
            $notificationCode = $parsedBody['notificationCode'];


            if ($notificationCode != null) {
                $access = [
                    'email' => 'professorramayana@gmail.com',
                    'token' => '6C461B67BD034EE8A9B1BA64E40F744D',
                    'notificationCode' => $notificationCode
                ];

                $pag_seguro = new BrPayments\Notifications\PagSeguro($access);
                $pag_seguro_request = new BrPayments\Requests\PagSeguroNotification;

                $response = (new BrPayments\MakeRequest($pag_seguro_request))->make($pag_seguro, true);
                $xml = new \SimpleXMLElement((string)$response);

                if ($xml->status == 3 || $xml->status == 4) {
                    $transaction = Transacao::where('ref', $xml->reference)->first();
                    if (!is_null($transaction)) {
                        $transaction->is_paid = true;
                        $transaction->payment_date = $xml->lastEventDate;
                        $transaction->save();
                        $usuario = Usuario::find($transaction->usuarios_id);

                        if (!is_null($usuario)) {
                            $usuario->access_expiration_date = date($xml->lastEventDate, strtotime('+6 month'));
                            $usuario->save();
                            sendPaymentConfirmationEmail($usuario);
                        } else {
                            error_log('Transação sem usuário: ' . (string)$response);
                        }
                    } else {
                        error_log('Transação não encontrada: ' . (string)$response);
                    }
                }
            } else {
                error_log(json_encode($parsedBody));
            }
        }
    } else {
        error_log('Nenhum parametro enviado - pagseguro notification');
    }
});

$app->post('/pagseguro/payment_check', function ($request, $response) {
    $parsedBody = $request->getParsedBody();
    error_log(json_encode($parsedBody));
});

$app->get('/pagseguro/payment_status/{reference}', function ($request, $response, $args) {
    if (isset($args['reference'])) {
        $access = [
            'email' => 'professorramayana@gmail.com',
            'token' => '6C461B67BD034EE8A9B1BA64E40F744D',
            'notificationCode' => $args['reference']
        ];

//        $access['notificationCode'] = $args['reference'];

        $pag_seguro = new BrPayments\Notifications\PagSeguro($access);
        $pag_seguro_request = new BrPayments\Requests\PagSeguroNotification;



        $response = (new BrPayments\MakeRequest($pag_seguro_request))->make($pag_seguro, true);
        $xml = new \SimpleXMLElement((string)$response);

        if ($xml->transaction->status == 3 || $xml->transaction->status == 4) {
            $transaction = Transacao::where('ref', $xml->transaction->reference)->first();
            if (!is_null($transaction)) {
                $transaction->is_paid = true;
                $transaction->payment_date = $xml->transaction->lastEventDate;
                $transaction->save();

                $usuario = Usuario::find($transaction->usuarios_id);
                if (!is_null($usuario)) {
                    $usuario->access_expiration_date = date($xml->transaction->lastEventDate, strtotime('+6 month'));
                    $usuario->save();

                    sendPaymentConfirmationEmail($usuario);
                } else {
                    error_log('Transação sem usuário: ' . (string)$response);
                }
            } else {
                error_log('Transação não encontrada: ' . (string)$response);
            }
        }
    } else {
        echo 'Parametro não informado';
    }
});


$app->get('/pagseguro/venda', function ($request, $response) {

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
    $responsePagSeguro = (new BrPayments\MakeRequest($pag_seguro_request))->make($pag_seguro, true);

    $xml = new \SimpleXMLElement((string)$responsePagSeguro);

    $url = $pag_seguro_request->getUrlFinal($xml->code, true);

    $this->renderer->render($response, "/user/videos_pay_button.phtml",
        [
            'xml' => $xml,
            'url' => $url
        ]);
});


