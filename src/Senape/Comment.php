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
            'comment',
        ];
        foreach ($dtd as $item) {
            $result[$item] = array_key_exists($item, $comment) ? $comment[$item] : null;
        }
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
     * @param array $comment
     */
    public function add($comment) {
        // TODO: make sure that $comment is correct
        $this->storage->addComment($this->getNormalized($comment));
    }
}
