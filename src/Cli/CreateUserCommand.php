<?php

namespace App\Cli;

use App\Db;
use Assert\Assertion;
use RedBeanPHP\R as R;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    protected function configure()
    {
        $this->setName('users:create');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $io->ask('Username', null, function ($input) {
            Assertion::string($input);
            Assertion::notBlank($input);
            $username = trim($input);
            Assertion::noContent(Db::find('user', 'username = ?', [$username]), 'Username is taken.');
            return $username;
        });
        $password = $io->ask('Password', null, function ($input) {
            Assertion::string($input);
            Assertion::notBlank($input);
            return $input;
        });
        $role = $io->choice('Role', [
            'p' => 'Przyjmujący',
            't' => 'Technik',
            'k' => 'Kontrola jakości',
            'a' => 'Artur',
        ]);
        $user = Db::dispense('user');
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_BCRYPT);
        $user->role = $role;
        Db::store($user);
        $io->success('User created.');
        return self::SUCCESS;
    }
}
