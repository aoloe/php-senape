<?php

namespace Aoloe\Senape\Storage;

class Json {
    private $settings = null;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    public function get_list() {
        return array(
            array (
                'title' => 'my comment',
                'content' => 'this is my "content"',
            ),
        );
    }
}
