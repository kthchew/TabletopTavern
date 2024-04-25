<?php

namespace Tabletop;

class Database extends \mysqli
{
    private static ?Database $instance = null;

    public static function getInstance(): Database
    {
        $database_config_file = __DIR__ . '/../database.ini';
        $config = parse_ini_file($database_config_file);
        if (!$config) {
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