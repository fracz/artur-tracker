<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$users = \App\Db::find('user');
$users = array_combine(
    array_map(fn($user) => $user->username, $users),
    array_map(fn($user) => $user->password, $users)
);

$view = new PhpRenderer(__DIR__ . '/../templates');
$view->setLayout('layout.php');

$app->add(new Tuupola\Middleware\HttpBasicAuthentication([
    "users" => $users,
    "before" => function ($request, $arguments) use ($view) {
        $user = \App\Db::findOne('user', 'username = ?', [$arguments['user']]);
        $view->addAttribute('user', $user);
        return $request->withAttribute("user", $user);
    }
]));

$app->get('/', function (Request $request, Response $response, $args) use ($view) {
    return $view->render($response, 'form.php', $args);
});

$app->run();
