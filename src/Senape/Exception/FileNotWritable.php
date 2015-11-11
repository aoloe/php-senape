<?php

namespace Aoloe\Senape\Exception;

class FileNotWritable Extends \Exception {
    private $fileNotWritten = null;

    public function __construct($path) {
        $this->fileNotWritten = $path;
        $this->message = "Could write the file ".$path;
    }

    public function getFileNotWritten() {
        return $this->fileNotWritten;
    }
}
