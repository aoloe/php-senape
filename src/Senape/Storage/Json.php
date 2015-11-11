<?php
/**
 * Json based storage engine for the comments.
 * One single Json file is used for all comments relating to a page.
 */

namespace Aoloe\Senape\Storage;

class Json extends Storage {

    /**
     * recursively walk through a dirctory path until it finds a writable directory and
     * create the missing directories in the path
     * @param string $path absolute path to the directory
     * TODO: probably move this to a utility function
     */
    private function ensureDirectoryWritable($path) {
        if (empty($path)) {
            throw new \Aoloe\Senape\Exception\FileNotWritable($path);
        } elseif (is_dir($path)) {
            if (is_writable($path)) {
                return;
            } else {
                throw new \Aoloe\Senape\Exception\FileNotWritable($path);
            }
        } elseif (is_file($path)) {
            throw new \Aoloe\Senape\Exception\FileNotWritable($path);
        } else {
            $this->ensureDirectoryWritable(dirname($path));
            mkdir($path);
            return;
        }
    }

    private function createPage($path) {
        // TODO: put in there a minimal valid file?
        $page = $this->settings['senape-page-current'];
        $page = [
            'title' => '',
            'url' => '',
            'status' => '',
            'last_key' => '',
            'comment' => [],
        ];
        file_put_contents($path, json_encode($page));
    }

    /**
     * create the path from the settings and make sure that the file exists
     */
    private function getFilePath() {
        $path = '';
        $site = $this->settings['senape-site-current'];
        $page = $this->settings['senape-page-current'];
        $path = $this->settings['senape-basepath-data'].$this->settings['storage-json-data-path'].$site.'/'.$page.'.json';
        \Aoloe\debug('path', $path);
        if (!file_exists($path)) {
            try {
                $this->ensureDirectoryWritable(dirname($path));
                $this->createPage($path);
            } catch (\Aoloe\Senape\Exception\FileNotWritable $e) {
                throw new \Aoloe\Senape\Exception\FileNotWritable($path);
            }
        }
        return $path;
    }

    public function getCommentList() {
        $result = array();
        // TODO: read the file
        $path = $this->getFilePath();
        // \Aoloe\debug('path', $path);
        $result = file_get_contents($path);
        $result = json_decode($result, true);
        return $result;
    }

    public function addComment($comments) {
        \Aoloe\debug('comments', $comments);
        $path = $this->getFilePath();
        \Aoloe\debug('path', $path);
    }
}
