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

    /**
     * Add the avatar based on the email address and make the date nicer to read.
     */
    private function getNicerComments($list) {
        foreach ($list as &$item) {
            // TODO: this hould not be here: use a static/namespaced function in Comment?
            if ($this->settings['ui-avatar-theme'] != 'none') {
                if ($item['email'] == '') {
                    $item['avatar'] = $this->settings['senape-http-theme-current'].'images/avatar.png';
                } else {
                    $size_icon = 45;
                    $gravatar_default = 'mm'; // which icon to use for the default: custom, 'mm', 'identicon', 'monsterid', 'wavatar', or 'retro', 'blank'
                    $item['avatar'] = $this->settings['senape-http-protcol'].'://gravatar.com/avatar/'.md5(strtolower(trim($item['email']))).'.png?r=pg&s='.$size_icon.'&d='.$gravatar_default;
                    // or https://secure.gravatar.com
                    // TODO: implement the default: our own icon... (only works if publicly accessible)
                    //&d='.($gravatar_theme == 'custom' ? urlencode($item['avatar']) : $gravatar_default).
                }
            }
            // TODO: this hould not be here: use a static/namespaced function in Comment?
            $now = time();
            $date = $item['date'];
            if ($now - $date <= 45 * 60) {
                $item['date'] = ceil(($now - $date) / 60 ).' minutes ago'; // TODO: translate this, with or without s
            } elseif ($now - $date < 12 * 60 * 60) {
                $item['date'] = ceil(($now - $date) / 60 / 60 ).' hours ago';
            } elseif ($date > strtotime('today')) {
                $item['date'] = 'today';
            } elseif ($date > strtotime('-1 day')) {
                $item['date'] = 'yesterday';
            } elseif ($date > strtotime('-7 day')) {
                $item['date'] = ceil(($now - $date) / 60 / 60 / 24).' days ago';
            } else {
                $item['date'] = date($this->settings['ui-date-format'], $date);
            }
            if (!empty($item['reply'])) {
                $item['reply'] = $this->getNicerComments($item['reply']);
            }
        }
        unset($item);
        return $list;
    }

    /**
     * Convert the associative arrays into ArrayIterators.
     */
    private function getListWithArrayIterator($list) {
        $result = [];
        foreach ($list as $item) {
            if (!empty($item['reply'])) {
                $item['reply'] = $this->getListWithArrayIterator($item['reply']);
            }
            $result[] = $item;
        }
        return new \ArrayIterator($result);
    }

    public function getList($list) {
        // \Aoloe\debug('settings', $this->settings);
        // \Aoloe\debug('list', $list);
        $mustache = new \Mustache_Engine(array(
            'loader' => new \Mustache_Loader_FilesystemLoader($this->path_template),
            'helpers' => array(
                'i18n' => \Aoloe\Senape\I18n::getInstance($this->settings),
            ),
        ));

        $list['comment'] = $this->getNicerComments($list['comment']);

        // for mustache, each associative array must be converted into ArrayIterator
        $list['comment'] = $this->getListWithArrayIterator($list['comment']);

        $template = $mustache->loadTemplate('comment-list');
        return $template->render(array(
            'list' => $list['comment'],
            // TODO: check if it's not better to put a fixed translatable string in the template and only pass the string in the settings if it is not null... wondering how to allow translations, then: with a custom translations file?
            'no-comment' => \Aoloe\Senape\I18n::getInstance($this->settings)->tr($this->settings['comment-message-no-comment']),
            'has-likes' => true, // TODO: read it from the settings
            'has-replies' => $this->settings['comment-reply'],
        ));
    }
    public function getAddComment() {
        $mustache = new \Mustache_Engine(array(
            'loader' => new \Mustache_Loader_FilesystemLoader($this->path_template),
            'helpers' => array(
                'i18n' => \Aoloe\Senape\I18n::getInstance($this->settings),
            ),
        ));
        $template = $mustache->loadTemplate('comment-form-add');

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
