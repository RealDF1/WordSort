<?php

namespace script;

use PDO;
use PDOException;

class DB
{
    private string $host = '127.0.0.1';
    private string $dbName = '';
    private string $userName = '';
    private string $password = '';
    private string $logFile = __DIR__ . "/../mysql.log";

    private static ?DB $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance(): DB
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect()
    {
        try {
            $connect = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->userName, $this->password);
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $connect;

        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            echo "Connection failed";
        }
    }

    private function logError($message): void
    {
        $currentDate = date('Y-m-d H:i:s');
        file_put_contents($this->logFile, "[$currentDate] $message" . PHP_EOL, FILE_APPEND);
    }

}