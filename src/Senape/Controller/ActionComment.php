<?php

namespace Aoloe\Senape\Controller;

class ActionComment extends \Aoloe\Senape\Controller {
    public function run() {
        \Aoloe\Debug('settings', $this->settings);
        \Aoloe\Debug('request', $this->request);
        if ($this->request['senape-action'] == 'add') {
            $comment = new \Aoloe\Senape\Comment($this->settings);
        }
    }
}
