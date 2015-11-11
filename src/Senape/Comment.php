<?php

namespace Aoloe\Senape;

class Comment {
    private $storage = null;

    public function __construct($settings) {
        if ($settings['storage'] === 'json') {
            $this->storage = new Storage\Json($settings);
        } elseif ($settings['storage'] === 'mysql') {
            $this->storage = new Storage\Mysql($settings);
        } else {
            // TODO: error
        }
    }

    public function getList() {
        return $this->storage->getList();
    }
}
