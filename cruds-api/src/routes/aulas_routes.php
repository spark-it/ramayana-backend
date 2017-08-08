<?php


include __DIR__ . '/../models/Aula.php';


$app->get('/forms/aulas/list', function ($request, $response, $args) {
    check_logged($response);
    $rows = Aula::all();

    $this->renderer->render($response, "/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/aulas/list.phtml", ['rows' => $rows,'base_url' => BASE_URL]);
    $this->renderer->render($response, "/foot.phtml", $args);
});

$app->get('/forms/aulas/create', function ($request, $response, $args) {
    check_logged($response);

    $this->renderer->render($response, "/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/aulas/create.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/foot.phtml", $args);
});

$app->get('/forms/aulas/edit/{id}', function ($request, $response, $args) {
    check_logged($response);

    $rows = Aula::find($args['id']);

    $this->renderer->render($response, "/head.phtml", ['base_url' => BASE_URL]);
    $this->renderer->render($response, "/aulas/edit.phtml", ['row' => $rows, 'base_url' => BASE_URL]);
    $this->renderer->render($response, "/foot.phtml", $args);
});

$app->post('/aulas/edit/{id}', function ($request, $response, $args) {
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
            return $response->withRedirect($base_url . '/forms/aulas/list');
        } else {
            return $response->withJson($texto, 201);
        }
    }
});



$app->get('/aulas', function ($request, $response, $args) {
    $rows = Aula::all();
    return $response->withJson($rows, 200);
});

//Get specific crud
$app->get('/aulas/{id}', function ($request, $response, $args) {
    $rows = Aula::find($args['id']);


    return $response->withJson($rows, 200);
});

$app->post('/aulas', function ($request, $response, $args) {
    $response_code = 201;

    $parsedBody = $request->getParsedBody();

    $title = $parsedBody['title'];
    $description = $parsedBody['description'];
    $text = $parsedBody['text'];

    $fromForm = $parsedBody['fromForm'];


    $files = $request->getUploadedFiles();
    $image = $files['image'];


    $errors = null;

    if ($title == null || empty($title)) {
        $response_code = 400;
        $errors['errors'][] = 'Title cannot be empty';

    } else if (strlen($title) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Title cannot have less than 3 characters';
    }


    if ($description == null || empty($description)) {
        $response_code = 400;
        $errors['errors'][] = 'Description cannot be empty';

    } else if (strlen($description) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Description cannot have less than 3 characters';
    }

    if ($text == null || empty($text)) {
        $response_code = 400;
        $errors['errors'][] = 'Text cannot be empty';

    } else if (strlen($text) < 3) {
        $response_code = 400;
        $errors['errors'][] = 'Text cannot have less than 3 characters';
    }


    if (!is_object($image)) {
        $response_code = 400;
        $errors['errors'][] = 'You need to send an image';
    } else if ($image->getError() !== UPLOAD_ERR_OK) {
        $response_code = 400;
        $errors['errors'][] = 'Image upload error';
    } else {
        //    } else if ($image->getError() === UPLOAD_ERR_OK) {
        $directory = $this->get('settings')['upload_dir'];
        $filename = moveUploadedFile($directory, $image);
        $response->write('uploaded ' . $filename . '<br/>');
        $image = $filename;
    }


    if ($response_code == 400) {
        return $response->withJson($errors, $response_code);
    } else {
        $result = Aula::create(array(
            'title' => $title,
            'description' => $description,
            'text' => $text,
            'image' => $image
        ));

        $base_url = $this->get('settings')['base_url'];
        $result->image = $base_url . '/uploads/' . $result->image;

        if($fromForm){
            return $response->withRedirect($base_url . '/forms/aulas/list'); 
        } else {
            return $response->withJson($result, 201);    
        }
    }
});

$app->put('/aulas/{id}', function ($request, $response, $args) {
    $response_code = 201;
    $aula = Aula::find($args['id']);
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

$app->delete('/aulas/{id}', function ($request, $response, $args) {
    if (Aula::destroy($args['id'])) {
        return $response->getBody()->write('', 200);
    } else {
        return $response->getBody()->write('Parâmetro não enviado', 400);
    }
});
