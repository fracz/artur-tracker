<?php

namespace App;

use RedBeanPHP\R as R;

R::setup('sqlite:' . __DIR__ . '/../var/sqlite.db');

class Db extends R
{
}


