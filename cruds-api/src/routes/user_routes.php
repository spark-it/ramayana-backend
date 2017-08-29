<?php

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;


$app->get('/user/login/{access_token}', function ($request, $response, $args) {
    $access_token = $args['access_token'];


    $fb = new Facebook([
        'app_id' => '1546114922113473',
        'app_secret' => '99485e0d80b47066811e201f4102450c',
        'default_graph_version' => 'v2.7',
    ]);

    try {
        // Returns a `Facebook\FacebookResponse` object
        $fb_response = $fb->get('/me?fields=id,first_name,last_name', $access_token);
        $user = $fb_response->getGraphUser();
        $user = Usuario::firstOrNew(
            [
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'facebook_id' => $user['id']
            ]
        );


        $_SESSION['user'] = $user;
        return $response->withRedirect(BASE_URL . '/user/videos/list');
    } catch (FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
});

$app->get('/user/pagseguro_payment_confirmation', function($request, $response){
    echo 'foi';
});

$app->get('/user/list_videos', function ($request, $response, $args) {
//    check_logged($response);

//    $rows = Texto::all();
    $this->renderer->render($response, "/user/list_videos.phtml", ['base_url' => BASE_URL]);
});