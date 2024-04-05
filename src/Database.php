<?php

namespace Tabletop;

class Database extends \mysqli
{
    private static $instance = null;

    public static function getInstance()
    {
        // read from database.ini
        $config = parse_ini_file('../database.ini');
        if ($config == false) {
            throw new \Exception('Error: cannot parse database.ini!');
        }
        $hostname = $config['host'];
        $username = $config['user'];
        $password = $config['password'];
        $database = $config['dbname'];
        $port = $config['port'];

        if (self::$instance == null) {
            self::$instance = new self($hostname, $username, $password, $database, $port);
        }
        return self::$instance;
    }
}