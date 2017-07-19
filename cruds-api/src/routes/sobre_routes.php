<?php


include __DIR__ . '/../models/Sobre.php';


//Get a list
$app->get('/sobre', function ($request, $response, $args) {
    $rows = Sobre::all();
    return $response->withJson($rows, 200);
});

//Get specific crud
$app->get('/sobre/{id}', function ($request, $response, $args) {
    $rows = Sobre::find($args['id']);
    return $response->withJson($rows, 200);
});




$app->post('/sobre', function ($request, $response, $args) {
    $response_code = 201;

    $parsedBody = $request->getParsedBody();
    $title = $parsedBody['title'];
    $description = $parsedBody['description'];
    $text = $parsedBody['text'];

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

    if (is_object($image)) {
        //    } else if ($image->getError() === UPLOAD_ERR_OK) {
        $directory = $this->get('settings')['upload_dir'];
        $filename = moveUploadedFile($directory, $image);
        $image = $filename;
        $data['image'] = $image;
    }

    if ($response_code == 400) {
        return $response->withJson($errors, $response_code);
    } else {
        $result = Sobre::create($data);

        if($result->image != null){
            $base_url = $this->get('settings')['base_url'];
            $result->image = $base_url . '/uploads/' . $result->image;
        } else{
            $result->image = null;
        }

        return $response->withJson($result, 201);
    }
});

$app->put('/sobre/{id}', function ($request, $response, $args) {
    $response_code = 201;
    $aula = Sobre::find($args['id']);
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

$app->delete('/sobre/{id}', function ($request, $response, $args) {
    if (Sobre::destroy($args['id'])) {
        return $response->getBody()->write('', 200);
    } else {
        return $response->getBody()->write('Parâmetro não enviado', 400);
    }
});
