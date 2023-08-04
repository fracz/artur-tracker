<?php

namespace App;

use Symfony\Component\Console\Application;

class Cli extends Application
{
    public function __construct()
    {
        parent::__construct('app');
        $this->addCommands($this->detectCommands(__DIR__ . '/Cli'));
    }

    private function detectCommands(string $directory): array
    {
        return array_filter(array_map(function (string $filename) {
            if (preg_match('#^(.+)Command.php$#', $filename, $match)) {
                $fullClassName = 'App\\Cli\\' . $match[1] . 'Command';
                return new $fullClassName();
            } else {
                return null;
            }
        }, scandir($directory)));
    }
}
