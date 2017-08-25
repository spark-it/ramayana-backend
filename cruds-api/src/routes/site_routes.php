<?php

include_once __DIR__ . '/../models/Aula.php';
include_once __DIR__ . '/../models/Texto.php';
include_once __DIR__ . '/../models/Informe.php';
include_once __DIR__ . '/../models/Sitio.php';

$app->get('/', function ($request, $response, $args) {
    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/index.phtml", ['assets_base' => BASE_URL . '/assets/']);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
});

$app->get('/aulas', function ($request, $response, $args) {
    $aula = Aula::query()->orderBy('created_at','desc')->first();

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
    $texto = Texto::query()->orderBy('created_at','desc')->first();

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/textos.phtml", ['assets_base' => BASE_URL . '/assets/','texto' => $texto]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
});



$app->get('/informes', function ($request, $response, $args) {
    $informe = Informe::query()->orderBy('created_at','desc')->first();

    $this->renderer->render($response, "/site/head.phtml", [
        'base_url' => BASE_URL,
        'assets_base' => BASE_URL . '/assets/'
    ]);
    $this->renderer->render($response, "/site/informes.phtml", ['assets_base' => BASE_URL . '/assets/','informe' => $informe]);
    $this->renderer->render($response, "/site/footer.phtml", ['assets_base' => BASE_URL . '/assets/']);
});

$app->get('/sitios', function ($request, $response, $args) {
    $sitio = Sitio::query()->orderBy('created_at','desc')->first();

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

