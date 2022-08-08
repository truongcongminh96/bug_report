<?php


namespace App\Helpers;


use App\Exception\NotFoundException;

class Config
{
    public static function get(string $fileName, string $key = null)
    {
        $fileContent = self::getFileContent($fileName);
        if ($key === null) {
            return $fileContent;
        }

        return isset($fileContent[$key]) ? $fileContent[$key] : [];
    }

    public static function getFileContent(string $fileName): array
    {
        $fileContent = [];
        try {
            $path = realpath(sprintf(__DIR__ . '/../Configs/%s.php', $fileName));
            if (file_exists($path)) {
                $fileContent = require $path;
            }
        } catch (\Throwable $exception) {
            throw new NotFoundException(
                sprintf('The specified file: %s was not found', $fileName)
            );
        }

        return $fileContent;
    }
}
