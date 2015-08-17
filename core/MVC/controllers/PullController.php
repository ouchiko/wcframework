<?php
/**
* Class and Function List:
* Function list:
* - init()
* Classes list:
* - PullController extends Controller
*/

use AppSpace\Controllers\Controller;
use AppSpace\Data\Mysql;

class PullController extends Controller {
    
    /**
     * Controller initialiser
     * @return type
     */
    public function init() {
        passthru($_SERVER['DOCUMENT_ROOT'] . "/../git-pull");
        $this->view("system/pullrequest.html", array());
    }
}
?>