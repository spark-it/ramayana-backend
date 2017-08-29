<?php

use src\models\Transacao;


include_once __DIR__ . '/../models/Aula.php';
include_once __DIR__ . '/../models/Texto.php';
include_once __DIR__ . '/../models/Informe.php';
include_once __DIR__ . '/../models/Sitio.php';
include_once __DIR__ . '/../models/Video.php';
include_once __DIR__ . '/../models/Transacao.php';

$app->get('/', function ($request, $response, $args) {
    $textos = Texto::query()->orderBy('created_at', 'desc')->limit(3)->get();
    $aulas = Aula::query()->orderBy('created_at', 'desc')->limit(3)->get();


    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/index.phtml",
        [
            'assets_base' => BASE_URL . '/assets/',
            'video_count' => Video::count(),
            'aula_count' => Aula::count(),
            'texto_count' => Texto::count(),
            'informe_count' => Informe::count(),
            'aulas_list' => $aulas,
            'textos_list' => $textos
        ]
    );
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL,]);
});

$app->get('/aulas', function ($request, $response, $args) {
    $aulas = Aula::all();

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/aulas_list.phtml", [
        'assets_base' => BASE_URL . '/assets/',
        'aulas' => $aulas
    ]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL,]);
});

$app->get('/aulas/{aula_id}', function ($request, $response, $args) {
    $aula_id = $args['aula_id'];
    $aula = Aula::find($aula_id);

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/aulas.phtml", [
        'assets_base' => BASE_URL . '/assets/',
        'aula' => $aula
    ]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL]);
});

$app->get('/textos', function ($request, $response, $args) {
    $textos = Texto::all();

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/textos_list.phtml", ['assets_base' => BASE_URL . '/assets/', 'textos' => $textos, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL]);
});

$app->get('/textos/{texto_id}', function ($request, $response, $args) {
    $texto_id = $args['texto_id'];
    $texto = Texto::find($texto_id);

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/textos.phtml", ['assets_base' => BASE_URL . '/assets/', 'texto' => $texto, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL,]);
});

$app->get('/informes', function ($request, $response, $args) {
    $informes = Informe::all();

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/informes_list.phtml", ['assets_base' => BASE_URL . '/assets/', 'informes' => $informes, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL,]);
});

$app->get('/informes/{informe_id}', function ($request, $response, $args) {
    $informe_id = $args['informe_id'];
    $informe = Informe::find($informe_id);

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/informes.phtml", ['assets_base' => BASE_URL . '/assets/', 'informe' => $informe, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL,]);
});

$app->get('/sitios', function ($request, $response, $args) {
    $sitio = Sitio::query()->orderBy('created_at', 'desc')->first();

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/sitios.phtml", ['assets_base' => BASE_URL . '/assets/', 'sitio' => $sitio, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL,]);
});

$app->get('/professor', function ($request, $response, $args) {
    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/professor.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL,]);
});

$app->get('/videos', function ($request, $response, $args) {
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];

        if(isset($user->id)){
            $temp_user = Usuario::find($user->id);
            $user->access_expiration_date = $temp_user->access_expiration_date;
        }


        $this->renderer->render($response, "/site/head.phtml", [
            'base_url' => BASE_URL,
            'assets_base' => BASE_URL . '/assets/'
        ]);

        if ($user->facebook_access_token != null) {
            error_log('facebook_not_null');
            try {
                $fb = new Facebook\Facebook(['app_id' => FACEBOOK_APP_ID, 'app_secret' => FACEBOOK_APP_SECRET, 'default_graph_version' => 'v2.10',]);
                $fb_response = $fb->get('/me?fields=id,first_name,last_name,email', $user->facebook_access_token);
                $fbUser = $fb_response->getDecodedBody();

                //Atualizando dados do usuário sempre que ele logar
                //pois é possível que o mesmo mude o e-mail do facebook
                $usuario = Usuario::where('facebook_id', $fbUser['id'])->first();
                if ($usuario == null) {
                    $usuario = new Usuario();
                }

                $usuario->first_name = $fbUser['first_name'];
                $usuario->last_name = $fbUser['last_name'];
                $usuario->facebook_id = $fbUser['id'];
                $usuario->email = $fbUser['email'];
                $usuario->facebook_id = $fbUser['id'];
                $usuario->facebook_access_token = $fb_response->getAccessToken();
                $usuario->save();

                if ($user->access_expiration_date >= date('Y-m-d')) {
                    $videos = Video::all();
                    $this->renderer->render($response, "/site/videos_logged.phtml", ['assets_base' => BASE_URL . '/assets/', 'videos' => $videos, 'base_url' => BASE_URL]);
                } else {
                    if (!$user->transaction_ref) {
                        $transaction = new Transacao();
                        $transaction->ref = uniqid('Ref-', true);
                        $transaction->product = 'Videos Ramayana';
                        $transaction->value = 40.00;
                        $transaction->usuarios_id = $user->id;
                        $transaction->save();

                        $user->transaction_id = $transaction->id;
                        $user->transaction_ref = $transaction->ref;
                        $user->transaction_product = $transaction->product;
                        $user->transaction_value = $transaction->value;
                        $user->transaction_is_paid = $transaction->is_paid;
                        $user->transaction_payment_date = $transaction->payment_date;
                        $_SESSION['user'] = $user;
                    }


                    $access = [
                        'email' => 'professorramayana@gmail.com',
                        'token' => '6C461B67BD034EE8A9B1BA64E40F744D',
                        'currency' => 'BRL',
                        'reference' => $user->transaction_ref
                    ];

                    $pag_seguro = new BrPayments\Payments\PagSeguro($access);
                    $pag_seguro_request = new BrPayments\Requests\PagSeguro;

                    error_log(json_encode($user));


                    //name, areacode, phone, email
                    $user_full_name = $user->first_name . ' ' . $user->last_name;
                    $pag_seguro->customer($user_full_name, '', '', $user->email);
                    $pag_seguro->shipping(1, '', '', '', '', '', '', '', 'ATA');
                    $pag_seguro->addProduct(1, $user->transaction_product, $user->transaction_value, 1);

                    //Request
                    $responsePagSeguro = (new BrPayments\MakeRequest($pag_seguro_request))->make($pag_seguro, true);

                    $xml = new \SimpleXMLElement((string)$responsePagSeguro);

                    $url = $pag_seguro_request->getUrlFinal($xml->code, true);

                    $this->renderer->render($response, "/site/videos_pay_button.phtml", ['xml' => $xml, 'url' => $url, 'base_url' => BASE_URL,'assets_base' => BASE_URL . '/assets/']);

                }

            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                error_log('Graph returned an error: ' . $e->getMessage());
                $this->renderer->render($response, "/site/videos.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL]);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                error_log('Facebook SDK returned an error: ' . $e->getMessage());
                $this->renderer->render($response, "/site/videos.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL]);
            }
            $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL,]);
        } else {
            error_log('facebook_null');
            $this->renderer->render($response, "/site/head.phtml", [
                'base_url' => BASE_URL,
                'assets_base' => BASE_URL . '/assets/'
            ]);
            $this->renderer->render($response, "/site/videos.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL]);
            $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL,]);
        }


    } else {
        $this->renderer->render($response, "/site/head.phtml", [
            'base_url' => BASE_URL,
            'assets_base' => BASE_URL . '/assets/'
        ]);
        $this->renderer->render($response, "/site/videos.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL]);
        $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/', 'base_url' => BASE_URL,]);
    }


});

$app->get('/videos/{accessToken}', function ($request, $response, $args) {
    $route = $request->getAttribute('route');
    $accessToken = $route->getArgument('accessToken');

    $fb = new Facebook\Facebook([
        'app_id' => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v2.10',
    ]);

    try {
        $fb_response = $fb->get('/me?fields=id,first_name,last_name,email', $accessToken);
        $fbUser = $fb_response->getGraphUser();

        $new_user = false;
        $usuario = Usuario::where('email', $fbUser['email'])->first();

        if ($usuario->id == null) {
            $new_user = true;
            $usuario = new Usuario();
        }

        $usuario->first_name = $fbUser['first_name'];
        $usuario->last_name = $fbUser['last_name'];
        $usuario->facebook_id = $fbUser['id'];
        $usuario->email = $fbUser['email'];
        $usuario->facebook_access_token = $accessToken;

        if (sendWelcomeMail($usuario->email)) {
            $usuario->welcome_email_sent = true;
        }
        $usuario->save();

        $transaction = new Transacao();

        if ($new_user) {
            $transaction->ref = uniqid('Ref-', true);
            $transaction->product = 'Videos Ramayana';
            $transaction->value = 40.00;
            $transaction->usuarios_id = $usuario->id;
            $transaction->save();
        } else {
            $transaction = Transacao::where('usuarios_id', $usuario->id)
                ->orderBy('updated_at', 'desc')->first();
        }

        //Criando sessão
        $stdUser = new stdClass();
        $stdUser->id = $usuario->id;
        $stdUser->first_name = $usuario->first_name;
        $stdUser->last_name = $usuario->last_name;
        $stdUser->email = $usuario->email;
        $stdUser->facebook_id = $usuario->facebook_id;
        $stdUser->facebook_access_token = $usuario->facebook_access_token;
        $stdUser->welcome_email_sent = $usuario->welcome_email_sent;
        $stdUser->transaction_id = $transaction->id;
        $stdUser->transaction_ref = $transaction->ref;
        $stdUser->transaction_product = $transaction->product;
        $stdUser->transaction_value = $transaction->value;
        $stdUser->transaction_is_paid = $transaction->is_paid;
        $stdUser->transaction_payment_date = $transaction->payment_date;

        $_SESSION['user'] = $stdUser;

    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    return $response->withRedirect(BASE_URL . '/videos');

});