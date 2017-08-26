<?php

include_once __DIR__ . '/../models/Aula.php';
include_once __DIR__ . '/../models/Texto.php';
include_once __DIR__ . '/../models/Informe.php';
include_once __DIR__ . '/../models/Sitio.php';
include_once __DIR__ . '/../models/Video.php';

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
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
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
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
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
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
});

$app->get('/textos', function ($request, $response, $args) {
    $textos = Texto::all();

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/textos_list.phtml", ['assets_base' => BASE_URL . '/assets/', 'textos' => $textos]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
});


$app->get('/textos/{texto_id}', function ($request, $response, $args) {
    $texto_id = $args['texto_id'];
    $texto = Texto::find($texto_id);

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/textos.phtml", ['assets_base' => BASE_URL . '/assets/', 'texto' => $texto]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
});




$app->get('/informes', function ($request, $response, $args) {
    $informes = Informe::all();

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/informes_list.phtml", ['assets_base' => BASE_URL . '/assets/', 'informes' => $informes]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
});


$app->get('/informes/{informe_id}', function ($request, $response, $args) {
    $informe_id = $args['informe_id'];
    $informe = Informe::find($informe_id);

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/informes.phtml", ['assets_base' => BASE_URL . '/assets/', 'informe' => $informe]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
});



$app->get('/sitios', function ($request, $response, $args) {
    $sitio = Sitio::query()->orderBy('created_at', 'desc')->first();

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/sitios.phtml", ['assets_base' => BASE_URL . '/assets/', 'sitio' => $sitio]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
});

$app->get('/professor', function ($request, $response, $args) {
    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/professor.phtml", ['assets_base' => BASE_URL . '/assets/']);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
});

$app->get('/videos', function ($request, $response, $args) {
    $user = $_SESSION['user'];

    if ($user->facebook_access_token != null) {
        try {
            $fb = new Facebook\Facebook([
                'app_id' => FACEBOOK_APP_ID,
                'app_secret' => FACEBOOK_APP_SECRET,
                'default_graph_version' => 'v2.10',
            ]);

            $fb_response = $fb->get('/me?fields=id,first_name,last_name,email', $user->facebook_access_token);
            $videos = Video::all();

            $this->renderer->render($response, "/site/head.phtml", [
                'base_url' => BASE_URL,
                'assets_base' => BASE_URL . '/assets/'
            ]);
            $this->renderer->render($response, "/site/videos_logged.phtml", ['assets_base' => BASE_URL . '/assets/', 'videos' => $videos]);
            $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);


        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }


    } else {
        $this->renderer->render($response, "/site/head.phtml", [
            'base_url' => BASE_URL,
            'assets_base' => BASE_URL . '/assets/'
        ]);
        $this->renderer->render($response, "/site/videos.phtml", ['assets_base' => BASE_URL . '/assets/']);
        $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
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


        $user = Usuario::firstOrNew([
            'email' => $fbUser['email']
        ]);
        $user->first_name = $fbUser['first_name'];
        $user->last_name = $fbUser['last_name'];
        $user->facebook_id = $fbUser['id'];
        $user->facebook_access_token = $accessToken;

        if (!$user->welcome_email_sent && sendWelcomeMail($user->email)) {
            $user->welcome_email_sent = true;
        }

        $user->save();

        $stdUser = new stdClass();
        $stdUser->id = $user->id;
        $stdUser->first_name = $user->first_name;
        $stdUser->last_name = $user->last_name;
        $stdUser->facebook_id = $user->facebook_id;
        $stdUser->facebook_access_token = $user->facebook_access_token;
        $stdUser->welcome_email_sent = $user->welcome_email_sent;

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