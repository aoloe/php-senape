<?php

namespace Aoloe\Senape\Controller;

class ActionComment extends \Aoloe\Senape\Controller {
    public function run() {
        // \Aoloe\Debug('settings', $this->settings);
        // \Aoloe\Debug('request', $this->request);
        if ($this->request['senape-action'] == 'add') {
            if (array_key_exists('senape-form', $this->request)) {
                $form = $this->request['senape-form'];
                $comment = new \Aoloe\Senape\Comment($this->settings);
                $comment->add($this->request['senape-form']);
            }
        }
    }
}
