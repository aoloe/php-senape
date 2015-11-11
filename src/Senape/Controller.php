<?php

namespace Aoloe\Senape;

abstract class Controller {
    protected $settings = null;
    protected $request = null;
    public function __construct($settings, $request) {
        $this->settings = $settings;
        $this->request = $request;
    }
    abstract public function run();
}
