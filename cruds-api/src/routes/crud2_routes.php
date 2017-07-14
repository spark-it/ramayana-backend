<?php


include __DIR__ . '/../models/Crud2.php';


//Get a list
$app->get('/crud2', function ($request, $response, $args) {
    $rows = Crud2::all();
    return $response->withJson($rows, 200);
});

//Get specific crud
$app->get('/crud2/{id}', function ($request, $response, $args) {
    $rows = Crud2::find($args['id']);
    return $response->withJson($rows, 200);
});

$app->post('/crud2', function ($request, $response, $args) {
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
        $result = Crud2::create(array(
            'title' => $title,
            'description' => $description,
            'text' => $text,
            'image' => $image
        ));

        $base_url = $this->get('settings')['base_url'];
        $result->image = $base_url . '/uploads/' . $result->image;
        return $response->withJson($result, 201);
    }
});