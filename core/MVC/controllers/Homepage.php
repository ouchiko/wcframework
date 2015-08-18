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

class Homepage extends Controller {

    /**
     * Controller initialiser
     * @return type
     */
    public function init() {
    	//$this->pingPullRequest();
		 $data = array(
	        "pagedata" => array(
	            "scriptsource" => "var stickyhead = true;"
	        ) ,
	        "vars" => $this->variables->getAll()
	    );

        $this->view("system/homepage.html", $data);
    }

    public function doDockerPage() {
    	//$this->pingPullRequest();
		 $data = array(
	        "pagedata" => array(
	            "scriptsource" => "var stickyhead = false;"
	        ) ,
	        "vars" => $this->variables->getAll()
	    );

        $this->view("system/docker.html", $data);
    }

    public function doPostCodePage() {
    	//$this->pingPullRequest();
		 $data = array(
	        "pagedata" => array(
	            "scriptsource" => "var stickyhead = false;"
	        ) ,
	        "vars" => $this->variables->getAll()
	    );

        $this->view("system/postcoder.html", $data);
    }
}
?>