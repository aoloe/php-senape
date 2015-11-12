<?php
/**
 * Json based storage engine for the comments.
 * One single Json file is used for all comments relating to a page.
 */

namespace Aoloe\Senape\Storage;

class Json extends Storage {

    private $fileHandle = null;

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
        $page = $this->settings['senape-page-current'];
        // TODO: this is not the right place for define the content of the page...
        $page = [
            'title' => '',
            'url' => '',
            'status' => '', // open / locked / hidden
            'last_id' => 0,
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
        // \Aoloe\debug('path', $path);
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
        $path = $this->getFilePath();
        // \Aoloe\debug('path', $path);
        $result = file_get_contents($path);
        $result = json_decode($result, true);
        return $result;
    }

    /**
     * Read the list from the file. create it if it does not exist, yet.
     * Use flock to make sure that nobody does the same while we are adding the item to the list.
     * You always have to call writeCommentList() afterwards, to release the lock and close the file.
     */
    private function getCommentListForWrite() {
        $result = null;

        $path = $this->getFilePath();
        $this->fileHandle = fopen($path,'r+');

        // the truncate hack is needed for read writing and still keeping the lock
        // http://stackoverflow.com/questions/2450850/read-and-write-to-a-file-while-keeping-lock
        if (flock($this->fileHandle, LOCK_EX)) {
            $filesize = filesize($path);
            if ($filesize) {
                $list = fread($this->fileHandle, $filesize);
                $list = json_decode($list, true);
                if ($list) {
                    $result = $list;
                } else {
                    // TODO: throw an exception
                }
            } else {
                // TODO: throw an exception
            }
        } else {
            echo "Could not Lock File!";
        }
        return $result;
    }

    /**
     * write the list in the json file by using the handle created by getCommentListForWrite()
     */
    private function writeCommentList($list) {
        if ($this->fileHandle) {
            $list = json_encode($list);
            ftruncate($this->fileHandle, 0);
            rewind($this->fileHandle);
            fwrite($this->fileHandle, $list);
            flock($this->fileHandle, LOCK_UN);
            fclose($this->fileHandle);
        } else {
            // TODO:: throw an exception
        }
    }

    /**
     * read the stored list, add the comment, store the list.
     */
    public function addComment($comment) {
        // \Aoloe\debug('comment', $comment);

        // \Aoloe\debug('remove the fake reply-to-id');
        // $comment['reply-to-id'] = 1;

        $list = $this->getCommentListForWrite();

        $comment['id'] = ++$list['last_id'];
        $comment['hash'] = md5(bin2hex(openssl_random_pseudo_bytes(12)));

        // TODO: for replies, write it below the parent
        if (array_key_exists('reply-to-id', $comment) && ($comment['reply-to-id'] > 0)) {
            $this->addToParentComment($list['comment'], $comment);
        } else {
            $list['comment'][$comment['id']] = $comment;
        }
        // \Aoloe\debug('list', $list);
        // \Aoloe\debug('temporary disabled the writing of the list');
        $this->writeCommentList($list);
        return $comment;
    }

    /**
     * Recursively look for the comment that has the id matching reply-to-id and attach
     *  the comment to its 'reply' list
     */
    private function addToParentComment(&$list, $comment) {
        foreach ($list as $key => $value) {
            if ($value['id'] == $comment['reply-to-id']) {
                unset($comment['reply-to-id']);
                $list[$key]['reply'][$comment['id']] = $comment;
                return true;
            } elseif (!empty($value['reply'])) {
                if ($this->addToParentComment($list, $comment)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getSiteList() {
        $list = null;
        $path = $this->settings['senape-basepath-data'].$this->settings['storage-json-data-path'].'site.json';
        if (!file_exists($path)) {
            if (is_writable(dirname($path))) {
                $list = array();
                file_put_contents($path, json_encode($list));
            } else {
                throw new \Aoloe\Senape\Exception\FileNotWritable($path);
            }
        } else {
            $list = file_get_contents($path);
            $list = json_decode($list, true);
            \Aoloe\debug('list', $list);
            if ($list === false) {
                throw new \Aoloe\Senape\Exception\FileInvalid($path);
            }
        }
        return $list;
    }
}
