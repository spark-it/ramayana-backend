<?php

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
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
    error_log('Chegou notificacao: ' . json_encode(print_r($parsedBody, true)));

    if (isset($parsedBody['notificationType'])) {

        $notificationType = $parsedBody['notificationType'];
        if ($notificationType == 'transaction') {

            $notificationCode = $parsedBody['notificationCode'];


            if ($notificationCode != null) {
                error_log('entrou3');
                $access = [
                    'email' => 'professorramayana@gmail.com',
//                    'token' => '6C461B67BD034EE8A9B1BA64E40F744D',
                    'token' => '3877C0360E424D028FAEF707F5A32D40',
                    'notificationCode' => $notificationCode
                ];

                $pag_seguro = new BrPayments\Notifications\PagSeguro($access);

                $pag_seguro_request = new BrPayments\Requests\PagSeguroNotification;

                try {
                    $response = (new BrPayments\MakeRequest($pag_seguro_request))->make($pag_seguro);
                    error_log('Notificacao - response: ' . (string)$response);


                    $xml = new \SimpleXMLElement((string)$response);
                    error_log('entrou8');
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
                    } else {
                        error_log('Notificacao - xml: ' . print_r($xml, true));
                    }

                } catch (ClientException $e) {
                    //do some thing here
                    error_log('Exception: ' . $e->getMessage());
                } catch (RequestException $e) {
                    error_log('Exception: ' . $e->getMessage());
                } catch (Exception $e) {
                    error_log('Exception: ' . $e->getMessage());
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
//            'token' => 'F5BBE6664848B13444433FAA29F346DC',
            'token' => '3877C0360E424D028FAEF707F5A32D40',
            'notificationCode' => $args['reference']
        ];

//        $access['notificationCode'] = $args['reference'];

        $pag_seguro = new BrPayments\Notifications\PagSeguro($access);
        $pag_seguro_request = new BrPayments\Requests\PagSeguroNotification;


        $response = (new BrPayments\MakeRequest($pag_seguro_request))->make($pag_seguro);
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