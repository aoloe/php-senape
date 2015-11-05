<?php

namespace Aoloe\Senape;

class Comment {
    private $storage = null;

    public function __construct($settings, $page, $site = null) {
        if ($settings['storage'] === 'json') {
            $this->storage = new Storage\Json($settings);
        } elseif ($settings['storage'] === 'mysql') {
            $this->storage = new Storage\Mysql($settings);
        } else {
            // TODO: error
        }
        $this->storage->set_client_page($page);
        $this->storage->set_client_site(is_null($site) ? $_SERVER['SERVER_NAME'] : $site);
    }

    public function get_list() {
        return $this->storage->get_list();
    }
}
