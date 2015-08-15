<?php
/**
* Class and Function List:
* Function list:
* - init()
* - create()
* - doWankSystem()
* Classes list:
* - MyAppController extends Controller
*/
	
use AppSpace\Controllers\Controller;
use MVC\models;

class PHPFrameDocumentationController extends Controller
{
    public function init()
    {
    	$available_pages = array(
    		"introduction", "controllers"
    	);

    	$data = $this -> getPageDataBasics("Documentation Basic");

    	$data['pageview'] =  "documentation/pages/" . (( in_array($this -> variables -> get("get.page"), $available_pages )) ?  $_GET['page'] : "introduction") . ".html";

        $this -> view("documentation/index.html", $data);
    }
    
    /**
     * Obtain the page basic elements
     * @param type $documentation_title 
     * @return type
     */
   	public function getPageDataBasics( $documentation_title ){
   		/** Core page data **/
        $data = array(
            "pagedata" => array(
                "stylesheets" => array(
                    "/css/_appviews/errors.css",
                    "/css/fontawesome/css/font-awesome.min.css",
                    "/css/documentation.css",
                    "/css/coresite.css"
                ) ,
                "title" => "Documentation | " . $documentation_title,
                "keywords" => "documentation,phpframe,framework,mvc",
                "description" => "Documentation centre for PHPFrame"
            )
        );

        return $data;
   	}
}
?>