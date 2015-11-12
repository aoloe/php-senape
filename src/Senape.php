<?php

namespace Aoloe;

class Senape
{

    private $settings = array();
    private $request = array(
        'site' => null, // TODO: are those two really needed?
        'page' => null,
    );
    private $basePath = null;

    public function __construct() {
        $this->basePath = rtrim(__DIR__, '/').'/';
        $this->settings = $this->getSettingsDefault();
    }

    /**
     * accept local settings and make sure that all settings are set to a default value
     * @param array $settings associative array with local settings
     */
    public function setSettings($settings) {
        // TODO: sanitize the data in $settings: make sure that all path finish in '/'...
        foreach (array('senape-basepath', 'senape-basepath-data') as $item) {
            if (array_key_exists($item, $settings)) {
                $settings[$item] = rtrim($settings[$item], '/').'/';
            }
        }
        $this->settings = $settings + $this->settings;
    }

    /**
     * @param string $settingsFilename
     */
    public function setSettingsFromFile($settingsFilename) {
        $settings = null;
        if (file_exists($settingsFilename)) {
            $settings = file_get_contents($settingsFilename);
        }
        if ($settings) {
            $settings = json_decode($settings);
        }
        if ($settings) {
            $this->setSettings($settings);
        }
    }

    public function initialize() {
        date_default_timezone_set ($this->settings['php-timezone']);
        mb_internal_encoding ('UTF-8');
        // debug('_SERVER', $_SERVER);

        if (isset($this->settings['senape-basepath-loading-file'])) {
            // TODO: this only works when Senape is a subdirectory of the loader... if we need more, we will implement it...
            $pathToSenape = rtrim(substr(__DIR__, strlen(dirname($this->settings['senape-basepath-loading-file']))), '/').'/';
        } else {
            $pathToSenape = 'vendor/aoloe/php-senape/';
        }
        // debug('pathToSenape', $pathToSenape);

        $this->settings['senape-http-protcol'] = $_SERVER['REQUEST_SCHEME'];
        $this->settings['senape-http-domain'] = $this->settings['senape-http-protcol'].'://'.rtrim($_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).'/'.dirname($pathToSenape), '/').'/'; // TODO: check if this is correct in all cases
        $this->settings['senape-mobile'] = $this->isMobile();
        if (is_null($this->settings['senape-basepath'])) {
            $this->settings['senape-basepath'] = dirname($this->basePath).'/';
        }
        $this->settings['senape-basepath-src'] = $this->basePath;
        if (is_null($this->settings['senape-basepath-themes'])) {
            $this->settings['senape-basepath-themes'] = $this->settings['senape-basepath'].'themes/';
        }
        if (is_null($this->settings['senape-http-themes'])) {
            $this->settings['senape-http-themes'] = $this->settings['senape-http-domain'].'themes/';
        }
        $this->settings['senape-http-theme-current'] = $this->settings['senape-http-themes'].$this->settings['ui-theme'].'/';
        if (is_null($this->settings['senape-basepath-data'])) {
            $this->settings['senape-basepath-data'] = $this->basePath;
        }

        $site_current = implode('.', array_slice(explode('.', $_SERVER['HTTP_HOST']), -2));
        if (is_null($this->settings['senape-site-valid-list'])) {
            $this->settings['senape-site-valid-list'] = [$site_current];
        }
        $this->settings['senape-site-current'] = $site_current; // default if setSite() is not called
    }
    
    public function setPage($page) {
        $this->settings['senape-page-current'] = $page;
    }

    public function setSite($site) {
        $this->settings['senape-site-current'] = $site;
    }

    /**
     * get the data from the request (most of the time $_REQUEST) and return a list of those values
     * that are valid, eventually with some default values set
     * TODO: to be implemented
     * TODO: remae it to something signifying that it cleans up the request
     */
    public function setRequest($request) {
        $this->request += $request;
    }

    public function readRequest() {
        if (array_key_exists('senape-page-current', $this->request)) {
            $this->settings['senape-page-current'] = $this->request['senape-page-current'];
        }
        if (array_key_exists('senape-page-site', $this->request)) {
            $this->settings['senape-page-site'] = $this->request['senape-page-site'];
        }
    }

    public function getActionController() {
        $result = null;
        if (array_key_exists('senape-controller', $this->request) && array_key_exists('senape-action', $this->request)) {
            // TODO: try to avoid the regexp
            $controller = preg_replace("/[^[:alnum:][:space:]]/u", '', $this->request['senape-controller']);
            $action = preg_replace("/[^[:alnum:][:space:]]/u", '', $this->request['senape-action']);
            debug('controller', $controller);
            debug('action', $action);
            if (!empty($controller) && !empty($action)) {
                $controllerClass = 'Senape\\Controller\\Action'.ucfirst($controller);
                debug('controllerClass', $controllerClass);
                $path = $this->settings['senape-basepath-src'].strtr($controllerClass, '\\', '/').'.php';
                debug('path', $path);
                if (is_readable($path)) {

                    $controllerClass = '\\Aoloe\\'.$controllerClass;
                    $result = new $controllerClass($this->settings, $this->request);
                }
                debug('result', $result);
            }
        }
        return $result;
    }

    public function getComments() {
        return new Senape\Comment($this->settings);
    }

    public function getSites() {
        return new Senape\Site($this->settings);
    }

    public function getViewHtml() {
        return new Senape\View\Html($this->settings);
    }

    public function getViewApi() {
        return new Senape\View\Api($this->settings);
    }

    /**
     * Read the settings from the settings.json file and return an key-value array with all the settings.
     */
    private function getSettingsDefault() {
        $result = array();
        $settings = json_decode(file_get_contents($this->basePath.'settings.json'), true);
        // debug('settings', $settings);
        if ($settings) {
            foreach ($settings as $key => $value) {
                $result[$key] = $value['value'];
            }
        }
        return $result;
    }

    private function isMobile() {
        // TODO: probably use an external library? or is it really enough?
        $result = false;
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            if (preg_match ('/(android|blackberry|phone)/i', $_SERVER['HTTP_USER_AGENT'])) {
                $result = true;
            }
        }
        return $result;
    }
}
