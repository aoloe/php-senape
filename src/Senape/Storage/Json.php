<?php

namespace Aoloe\Senape\Storage;

class Json extends Storage {
    private $settings = null;

    public function __construct($settings) {
        $this->settings = $settings;
        // \Aoloe\debug('settings', $settings);
    }

    public function getList() {
        // TODO: read the file
        $path = $this->settings['senape-basepath-data'].$this->settings['storage-json-data-path'].$this->getClientSite().'/'.$this->getClientPage().'.json';
        // \Aoloe\debug('path', $path);
        if (file_exists($path)) {
            $result = file_get_contents($path);
            $result = json_decode($content, true);
        } else {
            $result = array();
        }
        return $result;
    }

    public function addComment() {
    }
}
