<?php

use Slim\App;

return function (App $app) {

    $app->add(new \Tuupola\Middleware\HttpBasicAuthentication([
        "users" => [
            "root" => "t00r",
            "somebody" => "passw0rd"
        ]
    ]));

};
