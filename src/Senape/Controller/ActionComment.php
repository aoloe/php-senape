<?php

namespace Aoloe\Senape\Controller;

class ActionComment extends \Aoloe\Senape\Controller {
    public function run() {
        \Aoloe\Debug('settings', $this->settings);
        \Aoloe\Debug('request', $this->request);
        $comment = new \Aoloe\Senape\Comment($this->settings);
    }
}
