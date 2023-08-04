<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$view = new PhpRenderer(__DIR__ . '/../templates');
$view->setLayout('layout.php');

$app->get('/hello/{name}', function (Request $request, Response $response, $args) use ($view) {
    return $view->render($response, 'hello.php', $args);
});

$app->run();
