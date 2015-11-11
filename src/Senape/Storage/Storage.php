<?php

namespace Aoloe\Senape\Storage;

/**
 * TODO:
 * - client* is not really a good name...
 */
abstract class Storage {
    protected $settings = null;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    /**
     * @return array a list of comments
     */
    public abstract function getList();
}
