<?php
/**
 * Json based storage engine for the comments.
 * One single Json file is used for all comments relating to a page.
 */

namespace Aoloe\Senape\Storage;

class Json extends Storage {

    private function getValidFilePath() {
        $path = '';
        $site = $this->settings['senape-site-current'];
        $page = $this->settings['senape-page-current'];
        $path = $this->settings['senape-basepath-data'].$this->settings['storage-json-data-path'].$site.'/'.$page.'.json';
        if (!file_exists($path)) {
            if (is_writable(dirname($path))) {
            } else {
                // TODO: throw an exception if the directory is not writable
                throw new \Aoloe\Senape\Exception\FileNotWritable($path);
            }
        }
        return $path;
    }

    public function getCommentList() {
        $result = array();
        // TODO: read the file
        $path = $this->getValidFilePath();
        // \Aoloe\debug('path', $path);
        $result = file_get_contents($path);
        $result = json_decode($content, true);
        return $result;
    }

    public function addComment($comments) {
        \Aoloe\debug('comments', $comments);
    }
}
