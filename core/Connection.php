<?php
namespace application\core;

use PDO;
use PDOException;

class Connection
{
    private static $instance;

    private final function __construct()
    {
        try
        {
            global $configurations;

            $connectionString = "mysql:host=".$configurations['database']['hostname'].";dbname=".$configurations['database']['database'];

            $options = [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            self::$instance = new PDO(
                $connectionString,
                $configurations['database']['username'],
                $configurations['database']['password'],
                $options
            );
        } catch (PDOException $exception)
        {
            $message = $exception->getMessage();

            die($message);
        }
    }

	public static function getInstance(): PDO
    {
        if (self::$instance === null)
        {
            new Connection();
        }

        return self::$instance;
    }
}

