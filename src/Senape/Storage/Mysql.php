<?php

namespace Aoloe\Senape\Storage;

class Mysql {
    private $settings = null;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    public function get_list() {
        return array();
    }
}

