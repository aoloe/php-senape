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
        foreach ($list['comment'] as &$item) {
            if ($item['email'] == '') {
                $item['avatar'] = $this->settings['senape-http-theme-current'].'images/avatar.png';
            } else {
                $size_icon = 45;
                $gravatar_default = 'mm'; // which icon to use for the default: custom, 'mm', 'identicon', 'monsterid', 'wavatar', or 'retro', 'blank'
                $item['avatar'] = 'http://gravatar.com/avatar/'.md5(strtolower(trim($item['email']))).'.png?r=pg&s='.$size_icon.'&d='.$gravatar_default;
                // or https://secure.gravatar.com
                // TODO: implement the default: our own icon... (only works if publicly accessible)
                //&d='.($gravatar_theme == 'custom' ? urlencode($item['avatar']) : $gravatar_default).
            }
        }
        unset($item);

        // TODO: check if it's not better to put a fixed translatable string in the template and only pass the string in the settings if it is not null... wondering how to allow translations, then: with a custom translations file?
        // \Aoloe\debug('list', $list);
        $template = $mustache->loadTemplate('comment-list');
        return $template->render(array('list' => $list['comment'], 'no-comment' => \Aoloe\Senape\I18n::getInstance($this->settings)->tr($this->settings['comment-message-no-comment'])));
    }
    public function getAddComment() {
        $mustache = new \Mustache_Engine(array(
            'loader' => new \Mustache_Loader_FilesystemLoader($this->path_template),
            'helpers' => array(
                'i18n' => \Aoloe\Senape\I18n::getInstance($this->settings),
            ),
        ));
        $template = $mustache->loadTemplate('form-add-comment');

        // \Aoloe\debug('settings', $this->settings);
        // TODO: translating in the template or here in php before handing over to the template?
        // TODO: pass the current values once and if we have them
        return $template->render(array(
            // 'has-name' => true,
            // 'name' => 'ale',
            'has-labels' => false, // TODO: read it from the settings
            'site-current' => $this->settings['senape-site-current'],
            'page-current' => $this->settings['senape-page-current'],
            'comment-last-id' => null, // TODO: the last id the user has seen on this page... 0 if it's the first one. is it really needed?
        ));
    }
}
