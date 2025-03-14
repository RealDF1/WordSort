<?php

namespace script;

use Generator;

class WordHandler
{
    private mixed $filePath;
    private const toEncode = "UTF-8";
    private const encoding = ['UTF-8', 'Windows-1251'];
    private string $letterFolder = __DIR__ . "/../library";

    public function __construct()
    {
        if (!is_dir($this->letterFolder)) {
            mkdir($this->letterFolder, 0777, true);
        }
    }

    public function filePathSet($filePath): self
    {
        $this->filePath = $filePath;
        return $this;
    }

    private function getWordFromFile(): Generator
    {
        $handle = fopen($this->filePath, "r");

        while (!feof($handle)) {
            $line = trim(fgets($handle));

            $detectedEncoding = mb_detect_encoding($line, self::encoding);

            // Если кодировка не совпадает, конвертируем
            if ($detectedEncoding !== self::toEncode) {
                $line = mb_convert_encoding($line, self::toEncode, $detectedEncoding);
            }

            yield $line;
        }

        fclose($handle);
    }

    public function processFile(): void
    {
        // Проверяем, существует ли файл
        if (!file_exists($this->filePath)) {
            die("Файл не найден: $this->filePath");
        }

        $this->processWord();
        echo "Обработка завершена!";
    }

    private function processWord(): void
    {
        $letterCounts = 0;
        $firstLetterOld = null;

        foreach ($this->getWordFromFile() as $word) {
            if ($word === '') continue;

            if (isset($firstLetter)) {
                $firstLetterOld = $firstLetter;
            }

            // Получаем первую букву слова
            $firstLetter = mb_substr(mb_strtolower($word), 0, 1);



            // Создаем папку для буквы, если ее нет
            $letterFolder = $this->letterFolder . "/$firstLetter";
            if (!is_dir($letterFolder)) {
                mkdir($letterFolder, 0777, true);
            }

            // Записываем слово в файл words.txt
            file_put_contents("$letterFolder/words.txt", $word . PHP_EOL, FILE_APPEND);

            // Подсчитываем количество букв в слове
            $letterCount = mb_substr_count(mb_strtolower($word), $firstLetter);

            // Обновляем счетчик букв
            $letterCounts += $letterCount;

            if ($firstLetter !== $firstLetterOld && $firstLetterOld) {
                $this->letterCountToFile($firstLetterOld, $letterCounts);
                $letterCounts = 0;
            }
        }
        $this->letterCountToFile($firstLetter, $letterCounts);
    }

    private function letterCountToFile($letter, $letterCount): void
    {
        // Записываем счетчики в файл count.txt для каждой буквы
        $letterFolder = $this->letterFolder . "/$letter";
        file_put_contents("$letterFolder/count.txt", "Количество букв '$letter': $letterCount" . PHP_EOL);
    }
}