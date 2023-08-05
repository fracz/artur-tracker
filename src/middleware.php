<?php

/** @var \Slim\App $app */
/** @var \Slim\Views\PhpRenderer $view */

$users = \App\Db::find('user');
$users = array_combine(
    array_map(fn($user) => $user->username, $users),
    array_map(fn($user) => $user->password, $users)
);

$app->add(new Tuupola\Middleware\HttpBasicAuthentication([
    "users" => $users,
    "secure" => false,
    "before" => function ($request, $arguments) use ($view) {
        $user = \App\Db::findOne('user', 'username = ?', [$arguments['user']]);
        $view->addAttribute('user', $user);
        return $request->withAttribute("user", $user);
    }
]));
