<?php

use Slim\App;

return function (App $app) {

    $app->add(new \Tuupola\Middleware\HttpBasicAuthentication([
        "users" => [
            getenv("HTTP_AUTH_USER") => getenv("HTTP_AUTH_PASSWORD")
        ]
    ]));

};
