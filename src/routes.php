<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

return function (App $app) {

    $container = $app->getContainer();

    $app->post('/upload-hands',  \PokerHands\Controller\ImportController::class  . ':fromFile');

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        $stats = $container->get("gameRoundService")->getStatistics();

        $args['playerOneCount'] = $stats[1];
        $args['playerTwoCount'] = $stats[2];

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
};
