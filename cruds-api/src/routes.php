<?php
// Routes


function moveUploadedFile($directory, $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

include 'routes/textos_routes.php';
include 'routes/crud2_routes.php';
include 'routes/crud3_routes.php';
include 'routes/crud4_routes.php';
include 'routes/crud5_routes.php';


