<?php
/**
* Class and Function List:
* Function list:
* - init()
* Classes list:
* - PullController extends Controller
*/

use AppSpace\Controllers\Controller;

class MYCONTROLLERNAME extends Controller {
   
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
	        ) ,
	        "vars" => $this->variables->getAll()
	    );

        $this->view("system/pullrequest.html", $data);
    }
}
?>