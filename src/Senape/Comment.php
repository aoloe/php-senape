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
        $this->storage->setClientPage($page);
        $this->storage->setClientSite($site);
    }

    public function getList() {
        return $this->storage->getList();
    }
}
