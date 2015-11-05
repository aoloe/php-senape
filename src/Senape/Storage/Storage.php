<?php

namespace Aoloe\Senape\Storage;

abstract class Storage {
    private $settings = null;

    private $client_page = null;
    private $client_site = null;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    public function set_client_page($page) {
        $this->client_page = $page;
    }

    protected function get_client_page($page) {
        return $this->client_page;
    }

    public function set_client_site($site) {
        return $this->client_site;
    }

    protected function get_client_site($site) {
        return $this->client_site;
    }

    /**
     * @return array a list of comments
     */
    public abstract function get_list();
}
