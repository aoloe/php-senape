<?php

namespace Aoloe\Senape\Storage;

class Json extends Storage {
    private $settings = null;

    public function __construct($settings) {
        $this->settings = $settings;
        \Aoloe\debug('settings', $settings);
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
