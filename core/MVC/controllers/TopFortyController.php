<?php
/**
* Class and Function List:
* Function list:
* - init()
* Classes list:
* - PullController extends Controller
*/

use AppSpace\Controllers\Controller;
use MVC\models\Top40Model;

class TopFortyController extends Controller {
    

	public function docs(){

		$data = array(
			"vars" => $this->variables->getAll(),
		);

		$this -> view("system/top40docs.html",$data);
	}

    /**
     * Controller initialiser
     * @return type
     */
    public function init() {
    	$top40 = new Top40Model();
    	$source = $top40 -> getTop40($this->variables->get("uri.extension"));

    	//$this->pingPullRequest();
		 $data = array(
	        "vars" => $this->variables->getAll(),
	        "source" => $source
	    );

        $this->view("system/top40.html", $data);
    }
}
?>