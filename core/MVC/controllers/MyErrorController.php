<?php
/**
 * Class and Function List:
 * Function list:
 * - init()
 * Classes list:
 * - MyErrorController extends Controller
 */

/**
 * PHP Framework
 * Default Error Controller (404/500 etc page errors)
 * This is called from the base routing file when a file is not present.
 */

use AppSpace\Controllers\Controller;

class MyErrorController extends Controller {
    public function init() {
        
        /** Core page data **/
        $data = array(
            "pagedata" => array(
                "stylesheets" => array(
                    "/css/errors/errors.css",
                ) ,
                "title" => "webcoding.co.uk | error 404",
                "keywords" => "error,page,my,website",
                "description" => "Webpage error"
            ) ,
            "error" => $_SERVER['REQUEST_URI']
        );
        
        $this->view("generic_error/error_base.html", $data);
    }
}
?>