<?php


include_once __DIR__ . '/../models/Video.php';

$app->get('/admin/videos/list', function ($request, $response, $args) {
    check_logged($response);

    $rows = Video::all();
    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/videos/list.phtml", ['rows' => $rows, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->get('/admin/videos/create', function ($request, $response, $args) {
    check_logged($response);
    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/videos/create.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->get('/admin/videos/edit/{id}', function ($request, $response, $args) {
    check_logged($response);
    $rows = Video::find($args['id']);

    $subject = $rows->video_link;
    $url = parse_url($subject);
    parse_str($url['query'], $query);
    $rows->video_id = $query['v'];


    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/videos/edit.phtml", ['row' => $rows, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->post('/admin/videos', function ($request, $response, $args) {
    $response_code = 201;

    $parsedBody = $request->getParsedBody();
    $title = $parsedBody['title'];
    $description = $parsedBody['description'];
    $text = $parsedBody['text'];
    $category = $parsedBody['category'];
    $video_link = $parsedBody['video_link'];


    $fromForm = $parsedBody['fromForm'];

//    var_dump($parsedBody);die();


    $errors = null;

    if ($title == null || empty($title)) {
        $response_code = 400;
        $errors['errors'][] = 'Title cannot be empty';

    } else if (strlen($title) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Title cannot have less than 3 characters';
    }

    if ($text == null || empty($text)) {
        $response_code = 400;
        $errors['errors'][] = 'Text cannot be empty';

    } else if (strlen($text) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Text cannot have less than 3 characters';
    }


    $data = array(
        'title' => $title,
        'text' => $text
    );


    if ($description != null) {
        $data['description'] = $description;
    }

    if ($category != null) {
        $data['category'] = $category;
    }

    if ($video_link != null) {
        $data['video_link'] = $video_link;
    }


    if ($response_code == 400) {
        return $response->withJson($errors, $response_code);
    } else {
        $result = Video::create($data);

        if ($fromForm) {
            return $response->withRedirect(BASE_URL . '/admin/videos/list');
        } else {
            return $response->withJson($result, 201);
        }
    }
});

$app->post('/admin/videos/edit/{id}', function ($request, $response, $args) {
    $response_code = 201;
    $video = Video::find($args['id']);
    if (is_null($video)) {
        return $response->withJson(null, 200);
    }

    $parsedBody = $request->getParsedBody();
    $title = $parsedBody['title'];
    $description = $parsedBody['description'];
    $text = $parsedBody['text'];
    $category = $parsedBody['category'];
    $video_link = $parsedBody['video_link'];

    $fromForm = $parsedBody['fromForm'];

    $errors = null;

    if ($title == null || empty($title)) {
        $response_code = 400;
        $errors['errors'][] = 'Title cannot be empty';

    } else if (strlen($title) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Title cannot have less than 3 characters';
    } else {
        $video->title = $title;
    }

    if ($text == null || empty($text)) {
        $response_code = 400;
        $errors['errors'][] = 'Text cannot be empty';

    } else if (strlen($text) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Text cannot have less than 3 characters';
    } else {
        $video->text = $text;
    }


    if ($description != null) {
        $video->description = $description;
    }

    if ($category != null) {
        $video->category = $category;
    }

    if ($video_link != null) {
        $video->video_link = $video_link;
    }


    $image = null;
    $files = $request->getUploadedFiles();


    if (is_array($files)) {
        $image = $files['image'];
    }
    if (is_object($image) && !empty($image->file)) {
        $directory = $this->get('settings')['upload_dir'];
        $filename = moveUploadedFile($directory, $image);
        $video->image = $filename;
    }

    if ($response_code == 400) {
        return $response->withJson($errors, $response_code);
    } else {
        $video->save();


        if ($fromForm) {
            return $response->withRedirect(BASE_URL . '/admin/videos/list');
        } else {
            return $response->withJson($video, 201);
        }
    }
});

$app->post('/admin/videos/delete', function ($request, $response, $args) {
    $parsedBody = $request->getParsedBody();
    $id = $parsedBody['id'];


    if (Video::destroy($id)) {
        return $response->withRedirect(BASE_URL . '/admin/videos/list');
    } else {
        return $response->getBody()->write('Parâmetro não enviado', 400);
    }
});
