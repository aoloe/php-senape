<?php

namespace Aoloe\Senape;

// TODO: how to load only once the language files? a static class?
// TODO: how to know which fields are not set?
class I18n {

    private static $instance = null;

    private $settings = null;

    public function __construct($settings) {
        $this->settings = $settings;
        // TODO: define the locales path
        // TODO: read the the current language according to the settings
        // - the browser/localization language
        // - the language defined in the settings
    }

    /**
     * @return an instance if I18n initialized with the standard settings. Do not use getInstance() when
     * looking for specific translations (testing, ...)
     */
    public static function getInstance($settings) {
        if (is_null(self::$instance)) {
            self::$instance = new I18n($settings);
        }
        return self::$instance;
    }

    public function translate($text) {
        return "«".$text."»";
    }

    /**
     * this is a utility method for the translate() method.
     * when called from a "mustache" context it does not get any parameter and expects to get back
     * a function that is called with the text to be translated and returns the translated text.
     */
    public function tr($text = null) {
        if (isset($text)) {
            return $this->translate($text);
        } else {
            return function($text) {
                return $this->translate($text);
            };
        }
    }
}
