<?php


namespace App\Helpers;


class Config
{
    public static function getFileContent(string $fileName): array
    {

        $fileContent = [];
        try {
            $path = realpath(sprintf(__DIR__ . '/../Configs/%s.php', $fileName));
            if (file_exists($path)) {
                $fileContent = require $path;
            }
        } catch (\Exception $exception) {
            die($exception->getMessage());
        }


        return $fileContent;
    }
}
