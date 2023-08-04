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

$app->get('/logout', function (Request $request, Response $response) use ($view) {
    $view->addAttribute('user', null);
    return $view->render($response->withStatus(401), 'logout.php');
});

$app->post('/confirm', function (Request $request, Response $response, $args) use ($view) {
    $body = $request->getParsedBody();
    \Assert\Assertion::isArray($body);
    \Assert\Assert::that($body)
        ->keyExists('nrNaprawy')
        ->keyExists('idPrzyjmujacego')
        ->keyExists('dataPrzyjecia')
        ->keyExists('model')
        ->keyExists('sn');
    $repair = \App\Db::findOne('repair', 'nr_naprawy = ?', [$body['nrNaprawy']]);
    if ($body['confirm'] ?? false) {
        if (!$repair) {
            $repair = \App\Db::dispense('repair');
            $repair->nrNaprawy = $body['nrNaprawy'];
            $repair->idPrzyjmujacego = $body['idPrzyjmujacego'];
            $repair->dataPrzyjecia = new \DateTime('@' . strtotime($body['dataPrzyjecia']));
            $repair->model = $body['model'];
            $repair->sn = $body['sn'];
            $repair->createdAt = new \DateTime();
        }
        $role = $request->getAttribute('user')->role;
        $repair["assigned_{$role}"] = $request->getAttribute('user');
        $repair["assigned_{$role}_on"] = new \DateTime();
        \App\Db::store($repair);
        return $response->withHeader('Location', '/')->withStatus(302);
    } else {
        return $view->render($response, 'confirm.php', [
            'params' => $body,
            'model' => $repair,
        ]);
    }
});

$app->run();
