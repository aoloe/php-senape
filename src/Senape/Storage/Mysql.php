<?php

namespace Aoloe\Senape\Storage;

class Json extends Storage {
    private $settings = null;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    public function get_list() {
        return array();
    }
}

