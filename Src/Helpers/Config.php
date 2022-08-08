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

        $path = realpath(sprintf(__DIR__ . '/../Configs/%s.php', $fileName));
        if (file_exists($path)) {
            $fileContent = require $path;
        } else {
            throw new NotFoundException(
                sprintf('The specified file: %s was not found', $fileName), [
                    'not found file',
                    'data is passed'
                ]
            );
        }

        return $fileContent;
    }
}
