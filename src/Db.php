<?php

namespace App;

use RedBeanPHP\R as R;

echo 'sqlite:' . __DIR__ . '/../var/sqlite.db';
R::setup('sqlite:' . __DIR__ . '/../var/sqlite.db');

class Db extends R
{
}


