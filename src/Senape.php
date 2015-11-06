<?php

namespace Aoloe;

class Senape
{

    private $settings = array();
    private $basePath = null;

    public function initialize() {
        date_default_timezone_set ($this->settings['php-timezone']);
        mb_internal_encoding ('UTF-8');
        // TODO: do not use settings for those below
        $this->settings['senape-http-domain'] = $_SERVER['HTTP_HOST']; // TODO: this cannot be correct
        $this->settings['senape-mobile'] = $this->isMobile();
        $this->settings['senape-basepath'] = dirname($this->basePath).'/';
        $this->settings['senape-basepath-src'] = $this->basePath;
        $this->settings['senape-basepath-themes'] = $this->settings['senape-basepath'].'themes/';
        $this->settings['senape-basepath-data'] = $this->basePath;
    }


    public function __construct() {
        $this->basePath = rtrim(__DIR__, '/').'/';

        $this->settings = $this->getSettingsDefault();
        /*
        $mustache = new \Mustache_Engine(array(
            'loader' => new \Mustache_Loader_FilesystemLoader(dirname(__DIR__).'/view'),
        ));
        // echo $mustachem->render('Hello {{planet}}', array('planet' => 'World!'));
        $template = $mustache->loadTemplate('senape');
        echo($template->render(array('title-level' => $this->settings['ui-title-level'])));
        */
        // debug('settings', $this->settings);


        // TODO: add rootDirectory and httpDirectory to the settings
        // TODO: check for mobile agent
        // TODO: add in the settings a list of domains that are allowed to query the comments (if nothging given it's the one who triggered the setup of the storage? multiple domains per storage possible?)
        // TODO: apply the few settings that are to be applied



        // Get parent directory
        // Technical settings
        /*
        $this->rootDirectory	= $dirname;		// Root directory for script
        $this->httpDirectory	= '/' . basename ($dirname);	// Root directory for HTTP

        $this->domain		= $_SERVER['HTTP_HOST'];	// Domain name for refer checking & notifications
        */

        // Check if visitor is on mobile device
    }

    /**
     * accept local settings and make sure that all settings are set to a default value
     * @param array $settings associative array with local settings
     */
    public function setSettings($settings) {
        // TODO: sanitize the data in $settings: make sure that all path finish in '/'...
        foreach (array('settings-basepath', 'settings-basepath-data') as $item) {
            if (array_key_exists($item, $settings)) {
                $settings[$item] = rtrim($settings[$item], '/').'/';
            }
        }
        $this->settings = $settings + $this->settings;
    }

    /**
     * @param string $setSettingsFromFile
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

    /**
     * get the data from the request (most of the time $_REQUEST) and return a list of those values
     * that are valid, eventually with some default values set
     * TODO: to be implemented
     * TODO: remae it to something signifying that it cleans up the request
     */
    public function getRequest($request) {
        return array (
            'page' => null,
            'site' => null,
        ) + $request;
    }

    /**
     * @param string $page An identifier unique for the site (mostly and URI or a page title)
     * @param string $site The site accepting the comments. If null, the domain running the engine will be used.
     */
    public function getComments($page, $site = null) {
        return new Senape\Comment($this->settings, $page, $site);
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
        // debug('basePath', $this->basePath.'settings.json');
        // $settings = file_get_contents($this->basePath.'settings.json');
        // $parser = new \Seld\JsonLint\JsonParser();
        // $lint = $parser->lint(file_get_contents($this->basePath.'settings.json'));
        // if (isset($lint)) {
            // debug('lint', $lint->getDetails());
        // }
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
