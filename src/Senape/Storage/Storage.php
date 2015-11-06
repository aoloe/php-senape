<?php

namespace Aoloe\Senape\Storage;

/**
 * TODO:
 * - client* is not really a good name...
 */
abstract class Storage {
    private $settings = null;

    private $clientPage = null;
    private $clientSite = null;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    public function setClientPage($page) {
        $this->clientPage = $page;
    }

    protected function getClientPage() {
        return $this->clientPage;
    }

    public function setClientSite($site) {
        $this->clientSite = is_null($site) ? $_SERVER['SERVER_NAME'] : $site;
    }

    protected function getClientSite() {
        return $this->clientSite;
    }

    /**
     * @return array a list of comments
     */
    public abstract function getList();
}
