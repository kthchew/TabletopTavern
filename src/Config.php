<?php

namespace Tabletop;

class Config
{
    public static function getRootPath(): string
    {
        $config = parse_ini_file(__DIR__ . '/../config.ini');
        if (!$config) {
            return '/TabletopTavern/public';
        } else {
            return $config['root_path'];
        }
    }
}