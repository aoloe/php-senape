<?php

namespace Aoloe\Senape;

class Comment {
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

    /**
     * TODO: eventually, rename this method
     */
    private function getNormalized($comment) {
        $result = [];
        $dtd = [
            'name',
            'email',
            'website',
            'date',
            'comment',
            'status', // pending / approved / hidden
            'reply',
            'reply-to-id',
            'id',
            'hash',
            'likes',
        ];
        foreach ($dtd as $item) {
            $result[$item] = array_key_exists($item, $comment) ? $comment[$item] : null;
        }
        $result['status'] = 'pending'; // TODO: or automatically approve if the settings say so
        $result['date'] = time();
        $result['reply'] = array();
        return $result;
    }

    public function getList() {
        try {
            return $this->storage->getCommentList();
        } catch (\Aoloe\Senape\Exception\FileNotWritable $e) {
            // TODO: make the error messages translatable
            $this->error[] = 'The comment could not be read from '.basename($e->getFileNotWritten());
            return array();
        }
    }

    /**
     * get the list of comments to be consumed from javascript.
     * since the list of replies should be an array, it should not use the id as its index.
     */
    public function getListForJs() {
        $result = $this->getList();
        $result['comment'] = $this->getReplyAssociativeToArray($result['comment']);
        return $result;
    }

    private function getReplyAssociativeToArray($list) {
        $result = array();
        foreach ($list as $item) {
            if (!empty($item['reply'])) {
                $item['reply'] = $this->getReplyAssociativeToArray($item['reply']);
            }
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @param array $comment
     */
    public function add($comment) {
        // TODO: this is not the right place to catch the exception and build the errors list...
        try {
            $this->storage->addComment($this->getNormalized($comment));
            return true;
        } catch (\Aoloe\Senape\Exception\FileNotWritable $e) {
            // TODO: make the error messages translatable
            $this->error[] = 'The comment could not be written in '.basename($e->getFileNotWritten());
            \Aoloe\debug('error', $this->error);
            return false;
        }
    }
}
