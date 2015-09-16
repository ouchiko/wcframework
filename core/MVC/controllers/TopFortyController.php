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
    
	/**
	 * Process the documents
	 */
	public function docs(){

		$data = array(
			"vars" => $this->variables->getAll(),
			"pagedata" => array(
				"title" => "Top 40 in XML, JSON and Serialized",
                "description" => "The UK top 40 chart is all the formats you want",
                "keywords" => "web,development,vagrant,docker,php,lamp,css"
			)
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