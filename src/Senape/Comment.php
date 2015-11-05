<?php

namespace Aoloe\Senape;

class Comment {
    private $storage = null;
    public function __construct($settings) {
        if ($settings['php-storage'] === 'json') {
            $this->storage = new Storage\Json($settings);
        } elseif ($settings['php-storage'] === 'mysql') {
            $this->storage = new Storage\Mysql($settings);
        } else {
            // TODO: error
        }
    }

    public function get_list() {
        return $this->storage->get_list();
    }
}
