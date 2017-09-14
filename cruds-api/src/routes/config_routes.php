<?php
include_once __DIR__ . '/../models/Config.php';


//Get a list
$app->get('/admin/config/list', function ($request, $response, $args) {
    check_logged($response);

    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/config/list.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});


//Get specific crud
$app->get('/admin/config/edit/tempo_acesso', function ($request, $response, $args) {
    check_logged($response);
    $rows = Config::find(1); //Id do campo de tempo de acesso do usuário

    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/config/tempo_acesso.phtml", ['row' => $rows, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->get('/admin/config/edit/valor_acesso', function ($request, $response, $args) {
    check_logged($response);
    $rows = Config::find(2); //Id do campo de valor para acesso dos vídeos

    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);

    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/config/valor.phtml", ['row' => $rows, 'base_url' => BASE_URL, 'error' => $error_message]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->post('/admin/config/edit_valor', function ($request, $response, $args) {
    $config = Config::find(2);

    $parsedBody = $request->getParsedBody();

    $value = $parsedBody['value'];

    if ($value == null || empty($value) || $value == 0.00 || $value == '0.00') {
        $_SESSION['error'] = 'O valor não pode estar vazio';
        return $response->withRedirect(BASE_URL . '/admin/config/edit/valor_acesso');
    } else {
        $value = floatval(str_replace(',', '', $parsedBody['value']));
        $config->value = $value;
        $config->save();
    }

    return $response->withRedirect(BASE_URL . '/admin/config/list');
});

$app->post('/admin/config/edit_tempo', function ($request, $response, $args) {
    $config = Config::find(1);

    $parsedBody = $request->getParsedBody();

    $meses = $parsedBody['meses'];

    if ($meses == null || empty($meses)) {
        $_SESSION['error'] = 'O valor não pode estar vazio';
        return $response->withRedirect(BASE_URL . '/admin/config/edit/tempo_acesso');
    } else {
        $config->value = $meses;
        $config->save();
    }

    return $response->withRedirect(BASE_URL . '/admin/config/list');
});
