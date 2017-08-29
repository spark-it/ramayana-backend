<?php


include_once __DIR__ . '/../models/Aula.php';


$app->get('/admin/aulas/list', function ($request, $response, $args) {
    check_logged($response);
    $rows = Aula::all();

    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/aulas/list.phtml", ['rows' => $rows,'base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->get('/admin/aulas/create', function ($request, $response, $args) {
    check_logged($response);

    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/aulas/create.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->get('/admin/aulas/edit/{id}', function ($request, $response, $args) {
    check_logged($response);

    $rows = Aula::find($args['id']);

    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/aulas/edit.phtml", ['row' => $rows, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->post('/admin/aulas', function ($request, $response, $args) {
    $response_code = 201;

    $parsedBody = $request->getParsedBody();
    $title = $parsedBody['title'];
    $description = $parsedBody['description'];
    $text = $parsedBody['text'];

    $files = $request->getUploadedFiles();
    $image = $files['image'];

    $fromForm = $parsedBody['fromForm'];

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


    if (is_object($image) && !empty($image->file) ) {
        $directory = $this->get('settings')['upload_dir'];
        $filename = moveUploadedFile($directory, $image);
        $image = $filename;
        $data['image'] = $image;
    }


    if ($response_code == 400) {
        return $response->withJson($errors, $response_code);
    } else {
        $result = Aula::create($data);

        $base_url = $this->get('settings')['base_url'];
        $result->image = $base_url . '/uploads/' . $result->image;

        if($fromForm){
            return $response->withRedirect($base_url . '/admin/aulas/list');
        } else {
            return $response->withJson($result, 201);
        }
    }
});

$app->post('/admin/aulas/edit/{id}', function ($request, $response, $args) {
    $response_code = 201;
    $texto = Aula::find($args['id']);
    if (is_null($texto)) {
        return $response->withJson(null, 200);
    }

    $parsedBody = $request->getParsedBody();
    $title = $parsedBody['title'];
    $description = $parsedBody['description'];
    $text = $parsedBody['text'];

    $fromForm = $parsedBody['fromForm'];

    $errors = null;

    if ($title == null || empty($title)) {
        $response_code = 400;
        $errors['errors'][] = 'Title cannot be empty';

    } else if (strlen($title) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Title cannot have less than 3 characters';
    } else {
        $texto->title = $title;
    }

    if ($text == null || empty($text)) {
        $response_code = 400;
        $errors['errors'][] = 'Text cannot be empty';

    } else if (strlen($text) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Text cannot have less than 3 characters';
    } else {
        $texto->text = $text;
    }


    if ($description != null) {
        $texto->description = $description;
    }


    $image = null;
    $files = $request->getUploadedFiles();


    if (is_array($files)) {
        $image = $files['image'];
    }
    if (is_object($image) && !empty($image->file)) {
        $directory = $this->get('settings')['upload_dir'];
        $filename = moveUploadedFile($directory, $image);
        $texto->image = $filename;
    }

    if ($response_code == 400) {
        return $response->withJson($errors, $response_code);
    } else {
        $texto->save();

        if (!is_null($texto->image)) {
            $base_url = $this->get('settings')['base_url'];
            $texto->image = $base_url . '/uploads/' . $texto->image;
        }


        if ($fromForm) {
            return $response->withRedirect($base_url . '/admin/aulas/list');
        } else {
            return $response->withJson($texto, 201);
        }
    }
});

$app->post('/admin/aulas/delete', function ($request, $response, $args) {
    $parsedBody = $request->getParsedBody();
    $id = $parsedBody['id'];


    if (Aula::destroy($id)) {
        return $response->withRedirect(BASE_URL . '/admin/aulas/list');
    } else {
        return $response->getBody()->write('Parâmetro não enviado', 400);
    }
});