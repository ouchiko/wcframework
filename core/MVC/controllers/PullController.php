<?php
	

use AppSpace\Controllers\Controller;
use AppSpace\Data\Mysql;


class PullController extends Controller {
 
	public function init(){
		passthru($_SERVER['DOCUMENT_ROOT']."/../git-pull");
		$this -> view("system/pullrequest.html",array());
	}

}


?>