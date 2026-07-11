<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| SCANME CSRF Token Endpoint
| Version 1.0
|--------------------------------------------------------------------------
| Provides CSRF Token to frontend JavaScript
|--------------------------------------------------------------------------
*/


require_once __DIR__ . '/../../config/bootstrap.php';


header('Content-Type: application/json; charset=utf-8');


try {

    echo json_encode([
        "success" => true,
        "token"   => csrf_token()
    ]);


} catch (Throwable $e) {


    http_response_code(500);


    echo json_encode([
        "success" => false,
        "message" => "Unable to generate security token."
    ]);

}
