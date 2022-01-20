<?php

namespace App\Services;

class ImageService
{
    private $dupliacatedPath;

    public function __construct() {
        $this->dupliacatedPath = json_decode(file_get_contents("config.json"), true)['path']."/duplicated";
    }

    public function allImages(string $path, array $arrayFiles)
    {
        $files = dir($path);

        while ($file = $files->read()) {
            if ($this->isValid($file)) {
                if ($this->isDir($file)) {
                    $arrayFiles = $this->allImages(
                        "$path/$file",
                        $arrayFiles
                    );
                }
    
                if ($this->isFile($file)) {
                    array_push($arrayFiles, [
                        'file' => $file,
                        'path' => "$path/$file"
                    ]);
                }
            }
        }

        return $arrayFiles;
    }

    public function compareFiles(array $arrayFiles)
    {
        foreach ($arrayFiles as $keyFile => $file) {
            foreach ($arrayFiles as $keyFileToCompare => $fileToCompare) {
                if (
                    $this->exists($file)
                    && $this->isEqual($file, $fileToCompare)
                    && $keyFile !== $keyFileToCompare
                ) {
                    $this->move($file, $fileToCompare);
                    unset($arrayFiles[$keyFileToCompare]);
                }
            }
        }
    }

    private function exists(array $file)
    {
        return file_exists($file['path']);
    }

    private function isEqual(array $file, array $fileToCompare)
    {
        return md5_file($file['path']) === md5_file($fileToCompare['path']);
    }

    private function move(array $originalFile, array $moveFile)
    {
        $originalFile = explode('.', $originalFile["file"]);
        $fileName = $originalFile[0]."-".rand(0, 999999).".".$originalFile[1];
        rename(
            $moveFile['path'],
            $this->dupliacatedPath."/".$fileName
        );
    }

    private function isDir(string $file)
    {
        return strpos($file, '.') === false;
    }

    private function isFile(string $file)
    {
        return strpos($file, '.') !== false;
    }

    private function isValid(string $file)
    {
        return $file != '.' && $file != '..';
    }
}