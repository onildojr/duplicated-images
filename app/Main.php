<?php

namespace App;

use App\Services\ImageService;

class Main
{
    private $_imageService;
    private $path;

    public function __construct() {
        $this->_imageService = new ImageService();
        $this->path = json_decode(file_get_contents("config.json"), true)['path'];
    }

    public function start()
    {
        if (!file_exists($this->path."/duplicated")) {
            mkdir($this->path."/duplicated");
        }
        $arrayFiles = $this->_imageService->allImages($this->path, []);
        $this->_imageService->compareFiles($arrayFiles);
    }
}
