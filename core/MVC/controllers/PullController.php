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
		$fp = fopen("/srv/www/pullrequest.txt", "w");
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
    	//$this->pingPullRequest();
		 $data = array(
	        "pagedata" => array(
	            "stylesheets" => array(
	                "/css/system/core.css",
	            )
	            //"js" => array(
	             //   "/js/vendor/prism/prism.js"
	            //)
	        ) ,
	        "vars" => $this->variables->getAll()
	    );

        $this->view("system/pullrequest.html", $data);
    }
}
?>