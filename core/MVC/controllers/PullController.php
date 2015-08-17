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
    
	private function pingPullRequest(){
		$fp = fopen("/tmp/pullrequest.txt", "w");
		if ( $fp ) {
			fputs($fp, "1");
			fclose($fp);
		}
	}

    /**
     * Controller initialiser
     * @return type
     */
    public function init() {
    	$this->pingPullRequest();
        $this->view("system/pullrequest.html", array());
    }
}
?>