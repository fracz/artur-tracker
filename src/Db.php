<?php

namespace App;

use RedBeanPHP\R as R;

R::setup('sqlite:' . __DIR__ . '/../var/sqlite.db');
R::aliases(['assigned_p' => 'user', 'assigned_k' => 'user', 'assigned_t' => 'user']);

class Db extends R
{
}


