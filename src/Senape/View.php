<?php

namespace Aoloe\Senape;

abstract class View
{
    protected $settings = null;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    public abstract function getList($list);
    public abstract function getAddComment();
}
