<?php

namespace Aoloe\Senape;

class Site {
    private $settings = null;
    private $storage = null;
    private $error = array();

    public function __construct($settings) {
        $this->settings = $settings;

        if ($settings['storage'] === 'json') {
            $this->storage = new Storage\Json($settings);
        } elseif ($settings['storage'] === 'mysql') {
            $this->storage = new Storage\Mysql($settings);
        } else {
            // TODO: error
        }
    }

    public function getList() {
        try {
            return $this->storage->getSiteList();
        } catch (\Aoloe\Senape\Exception\FileNotWritable $e) {
            // TODO: make sure that the errors can be shown
            // TODO: make the error messages translatable
            $this->error[] = 'The comment could not be read from '.basename($e->getFileNotWritten());
            return array();
        }
    }
}
