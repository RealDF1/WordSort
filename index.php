<?php

require 'vendor/autoload.php';

use script\WordHandler;
use script\WordsDB;

error_reporting(0);

$file = new WordHandler();

// CLI режим
if (php_sapi_name() === 'cli') {
    if ($argc < 2) {
        die("Укажите путь к файлу: php index.php /path/to/file.txt");
    }
    $file->filePathSet($argv[1])->processFile();
    exit;
}

require_once 'src/importForm.html';

// Веб-режим
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            $file->filePathSet($filePath)->processFile();
        } else {
            echo "Ошибка загрузки файла.";
        }
        exit;
    } elseif (isset($_POST['insert'])) {
        $WordsDB = new WordsDB();
    }
}

