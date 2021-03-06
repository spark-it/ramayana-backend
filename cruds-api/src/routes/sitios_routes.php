<?php


include_once __DIR__ . '/../models/Sitio.php';


//Get a list
$app->get('/api/sitios', function ($request, $response, $args) {
    $rows = Sitio::all();
    return $response->withJson($rows, 200);
});

//Get specific crud
$app->get('/api/sitios/{id}', function ($request, $response, $args) {
    $rows = Sitio::find($args['id']);
    return $response->withJson($rows, 200);
});




$app->put('/api/sitios/{id}', function ($request, $response, $args) {
    $response_code = 201;
    $aula = Sitio::find($args['id']);
    if (is_null($aula)) {
        return $response->withJson(null, 200);
    }

    $title = null;
    $text = null;
    $description = null;
    $image = null;

    $parsedBody = $request->getParsedBody();
    if (array_key_exists('title', $parsedBody)) {
        $title = $parsedBody['title'];
    }
    if (array_key_exists('text', $parsedBody)) {
        $text = $parsedBody['text'];
    }
    if (array_key_exists('description', $parsedBody)) {
        $description = $parsedBody['description'];
    }

    $files = $request->getUploadedFiles();

    if (is_array($files) && array_key_exists('image', $files)) {
        $image = $files['image'];
    }
    if (is_object($image)) {
        $directory = $this->get('settings')['upload_dir'];
        $filename = moveUploadedFile($directory, $image);
        $aula->image = $filename;
    }


    $errors = null;

    if ($title == null || empty($title)) {
        $response_code = 400;
        $errors['errors'][] = 'Title cannot be empty';

    } else if (strlen($title) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Title cannot have less than 3 characters';
    } else {
        $aula->title = $title;
    }

    if ($text == null || empty($text)) {
        $response_code = 400;
        $errors['errors'][] = 'Text cannot be empty';

    } else if (strlen($text) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Text cannot have less than 3 characters';
    } else {
        $aula->text = $text;
    }


    if ($description != null) {
        $aula->description = $description;
    }


    if ($response_code == 400) {
        return $response->withJson($errors, $response_code);
    } else {
        error_log($aula->image);

        $aula->save();

        if ($aula->image != null) {
            $base_url = $this->get('settings')['base_url'];
            $aula->image = $base_url . '/uploads/' . $aula->image;
        }

        return $response->withJson($aula, 201);
    }
});

$app->delete('/api/sitios/{id}', function ($request, $response, $args) {
    if (Sitio::destroy($args['id'])) {
        return $response->getBody()->write('', 200);
    } else {
        return $response->getBody()->write('Parâmetro não enviado', 400);
    }
});


//admin

$app->get('/admin/sitios/list', function ($request, $response, $args) {
    check_logged($response);
    $rows = Sitio::all();

    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/sitios/list.phtml", ['rows' => $rows, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->get('/admin/sitios/create', function ($request, $response, $args) {
    check_logged($response);

    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/sitios/create.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->get('/admin/sitios/edit/{id}', function ($request, $response, $args) {
    check_logged($response);
    $rows = Sitio::find($args['id']);

    $this->renderer->render($response, "/admin/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/sitios/edit.phtml", ['row' => $rows, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/admin/foot.phtml", $args);
});

$app->post('/admin/sitios', function ($request, $response, $args) {
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
        $result = Sitio::create($data);

        if ($result->image != null) {
            $base_url = $this->get('settings')['base_url'];
            $result->image = $base_url . '/uploads/' . $result->image;
        } else {
            $result->image = null;
        }

        if ($fromForm) {
            return $response->withRedirect($base_url . '/admin/sitios/list');
        } else {
            return $response->withJson($result, 201);
        }
    }
});

$app->post('/admin/sitios/edit/{id}', function ($request, $response, $args) {
    $response_code = 201;
    $texto = Sitio::find($args['id']);
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
            return $response->withRedirect($base_url . '/admin/sitios/list');
        } else {
            return $response->withJson($texto, 201);
        }
    }
});


$app->post('/admin/sitios/delete', function ($request, $response, $args) {
    $parsedBody = $request->getParsedBody();
    $id = $parsedBody['id'];


    if (Sitio::destroy($id)) {
        return $response->withRedirect(BASE_URL . '/admin/sitios/list');
    } else {
        return $response->getBody()->write('Parâmetro não enviado', 400);
    }
});

