<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

session_start();

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$timezone = new \DateTimeZone('Europe/Warsaw');
date_default_timezone_set('Europe/Warsaw');

$view = new PhpRenderer(__DIR__ . '/../templates');
$flash = new \Slim\Flash\Messages();
$view->setLayout('layout.php');
$view->addAttribute('flash', $flash);

require __DIR__ . '/../src/middleware.php';

$app->get('/', function (Request $request, Response $response, $args) use ($flash, $view) {
    return $view->render($response, 'form.php', $args);
});

$app->get('/logout', function (Request $request, Response $response) use ($view) {
    $view->addAttribute('user', null);
    return $view->render($response->withStatus(401), 'logout.php');
});

$app->post('/confirm', function (Request $request, Response $response, $args) use ($flash, $timezone, $view) {
    $body = $request->getParsedBody();
    \Assert\Assertion::isArray($body);
    \Assert\Assert::that($body)
        ->keyExists('nrNaprawy')
        ->keyExists('idPrzyjmujacego')
        ->keyExists('dataPrzyjecia')
        ->keyExists('model')
        ->keyExists('sn');
    $repair = \App\Db::findOne('repair', 'nr_naprawy = ?', [$body['nrNaprawy']]);
    $role = $request->getAttribute('user')->role;
    $confirmed = ($body['confirm'] ?? false) || !!$repair;
    if ($confirmed && !$repair["assigned_{$role}"]) {
        if (!$repair) {
            $repair = \App\Db::dispense('repair');
            $repair->nrNaprawy = $body['nrNaprawy'];
            $repair->idPrzyjmujacego = $body['idPrzyjmujacego'];
            $repair->dataPrzyjecia = new \DateTime('@' . strtotime($body['dataPrzyjecia']), $timezone);
            $repair->model = $body['model'];
            $repair->sn = $body['sn'];
            $repair->createdAt = new \DateTime('now', $timezone);
        }
        $repair["assigned_{$role}"] = $request->getAttribute('user');
        $repair["assigned_{$role}_on"] = new \DateTime('now', $timezone);
        $repair->lastChangeOn = new \DateTime('now', $timezone);
        \App\Db::store($repair);
        $flash->addMessage('success', 'Naprawa została zapisana.');
        return $response->withHeader('Location', '/')->withStatus(302);
    } else {
        return $view->render($response, 'confirm.php', [
            'params' => $body,
            'model' => $repair,
        ]);
    }
});

$app->get('/list', function (Request $request, Response $response) use ($timezone, $view) {
    $role = $request->getAttribute('user')->role;
    $params = $request->getQueryParams();
    $params['finished'] = !!($params['finished'] ?? false);
    $params['dates'] = $params['dates'] ?? date('Y-m-01') . ' - ' . date('Y-m-d');
    $dates = explode(' - ', $params['dates']);
    \Assert\Assertion::count($dates, 2);
    $dateFrom = new \DateTime($dates[0], $timezone);
    $dateTo = new \DateTime($dates[1], $timezone);
    $where = [];
    $args = [];
    if ($role != 'a') {
        $where[] = "assigned_{$role}_id = :userId";
        $args['userId'] = $request->getAttribute('user')->id;
    }
    $where[] = "DATE(last_change_on) >= :from";
    $where[] = "DATE(last_change_on) <= :to";
    $args['from'] = $dateFrom->format('Y-m-d');
    $args['to'] = $dateTo->format('Y-m-d');
    if ($params['finished']) {
        $where[] = 'assigned_p_id IS NOT NULL AND assigned_t_id IS NOT NULL AND assigned_k_id IS NOT NULL';
    }
    $repairs = \App\Db::getAll('SELECT * FROM repair WHERE ' . implode(' AND ', $where), $args);
    $repairs = \App\Db::convertToBeans('repair', $repairs);
    return $view->render($response, 'list.php', ['repairs' => $repairs, 'params' => $params]);
});

$app->run();
