<?php

namespace App\HT\Services;

use Dotenv\Dotenv;

class DotenvLoader
{
    private static $instance = null;

    private $dotenv;

    private function __construct()
    {
        $this->dotenv = Dotenv::createImmutable(ABSPATH);
        $this->dotenv->load();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance->dotenv;
    }
}
