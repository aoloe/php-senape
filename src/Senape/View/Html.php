<?php

namespace Aoloe\Senape\View;

class Html extends \Aoloe\Senape\View
{
    private $path_template = null;

    public function __construct($settings) {
        parent::__construct($settings);
        $this->path_template = $this->settings['senape-basepath-themes'].$this->settings['ui-theme'].'/template/';
        // \Aoloe\debug('path', $this->path_template);
    }
    public function getList($list) {
        // \Aoloe\debug('settings', $this->settings);
        $mustache = new \Mustache_Engine(array(
            'loader' => new \Mustache_Loader_FilesystemLoader($this->path_template),
            'helpers' => array(
                /*
                'i18n' => function($text) {
                    \Aoloe\debug('text', $text);
                    return $text;
                },
                */
                'i18n' => \Aoloe\Senape\I18n::getInstance($this->settings),
            ),
        ));
        if (empty($list)) {
            $template = $mustache->loadTemplate('comment-list-empty');
            // TODO: check if it's not better to put a fixed translatable string in the template and only pass the string in the settings if it is not null... wondering how to allow translations, then: with a custom translations file?
            return $template->render(array('content' => \Aoloe\Senape\I18n::getInstance($this->settings)->tr($this->settings['comment-message-no-comment'])));
        } else {
            return print_r($list, 1);
        }
    }
    public function getAddComment() {
        $mustache = new \Mustache_Engine(array(
            'loader' => new \Mustache_Loader_FilesystemLoader($this->path_template),
            'helpers' => array(
                'i18n' => \Aoloe\Senape\I18n::getInstance($this->settings),
            ),
        ));
        $template = $mustache->loadTemplate('form-add-comment');
        // TODO: translating in the template or here in php before handing over to the template?
        // TODO: pass the current values once and if we have them
        return $template->render(array(
            // 'has-name' => true,
            // 'name' => 'ale',
            'page-url' => null, // TODO: the current page
            'comment-last-id' => null, // TODO: the last id the user has seen on this page... 0 if it's the first one. is it really needed?
        ));
    }
}
