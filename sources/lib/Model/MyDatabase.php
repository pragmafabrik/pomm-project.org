<?php #sources/lib/Model/MyDatabase.php

namespace Model;

use \Pomm\Connection\Database;

class MyDatabase extends Database
{
    protected function initialize()
    {
        parent::initialize();

        // register configuration or converters here
    }
}
