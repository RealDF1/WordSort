<?php

namespace script;

use PDO;
use script\DB;

class WordsDB
{
    protected PDO $connect;

    public function __construct()
    {
        $this->connect = DB::getInstance()->connect();
    }

    public function insert(): void
    {
        // Реализация добавления
    }

    public function update(): void
    {
        // Реализация обновления
    }

    public function delete(): void
    {
        // Реализация удаления
    }
}