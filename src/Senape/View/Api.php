<?php

namespace Aoloe\Senape\View;

class Api extends \Aoloe\Senape\View
{
    public function getList($list) {
        return json_encode($this->getResponse('get-comment-list', $list));
    }
    public function getAddComment() {
        return json_encode(array('a form'));
    }

    private function getResponse($action, $parameter) {
        return array(
            'api-version' => '0.1', // TODO: use a const // get it from composer.json
            'action' => $action,
            'parameter' => $parameter
        );
    }

}
