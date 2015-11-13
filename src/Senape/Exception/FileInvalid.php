<?php

namespace Aoloe\Senape\Exception;

class FileInvalid Extends \Exception {

    public function __construct($path) {
        $this->fileInvalid = $path;
        $this->message = "Could write the file ".$path;
    }

    public function getFileInvalid() {
        return $this->fileInvalid;
    }
}
