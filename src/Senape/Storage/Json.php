<?php

namespace Aoloe\Senape\Storage;

class Json extends Storage {

    public function getList() {
        // TODO: read the file
        $site = $this->settings['senape-site-current'];
        $page = $this->settings['senape-page-current'];
        $path = $this->settings['senape-basepath-data'].$this->settings['storage-json-data-path'].$site.'/'.$page.'.json';
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
