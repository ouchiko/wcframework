<?php
/**
 * Class and Function List:
 * Function list:
 * - basePage()
 * - init()
 * - doNewsOverTime()
 * - doDockerPage()
 * - doPostCodePage()
 * - doGeoMapPage()
 * Classes list:
 * - HomepageController extends Controller
 */

use AppSpace\Controllers\Controller;
use AppSpace\Data\Mysql;

class HomepageController extends Controller {
    
    private function basePage($view) {

        $data = array(
            "pagedata" => array(
                "scriptsource" => "var stickyhead = false;"
            ) ,
            "vars" => $this->variables->getAll()
        );
        

        switch ( $view ) {
            case "newsovertime.html" : 
                $data['pagedata']['title'] = "BBC News over time";
                $data['pagedata']['description'] = "Using screen capture and video to present news over time";
                $data['pagedata']['keywords'] = "news,scree,capture,webcoding,bbc,over,time";
            break;
             case "docker.html" : 
                $data['pagedata']['title'] = "Docker containers";
                $data['pagedata']['description'] = "Understanding and playing with Docker containers";
                $data['pagedata']['keywords'] = "docker,containers,managing,learning,deployment";
            break;
             case "postcoder.html" : 
                $data['pagedata']['title'] = "Postcode API";
                $data['pagedata']['description'] = "Bringing together open data to produce a free postcode API";
                $data['pagedata']['keywords'] = "postcode,api,free,uk,json,serialise,xml";
            break;
            case "geomapping.html" : 
                $data['pagedata']['title'] = "Geomapping Visualisations";
                $data['pagedata']['description'] = "Visualisations of the UK through various geo mapping techniques";
                $data['pagedata']['keywords'] = "ukgrid,postcode,visual,uk,naptan";
            break;
        }

        $this->view("system/" . $view, $data);
    }
    
    public function doWCFramework(){
        $this -> basePage("wcframework.html");
    }

    /**
     * Controller initialiser
     * @return type
     */
    public function init() {
        
        //$this->pingPullRequest();
        $data = array(
            "pagedata" => array(
                "scriptsource" => "var stickyhead = true;",
                "title" => "Developing stuff for 15 years",
                "description" => "Web development for @ouchy",
                "keywords" => "web,development,vagrant,docker,php,lamp,css"
            ) ,
            "vars" => $this->variables->getAll()
        );
        
        $this->view("system/homepage.html", $data);
    }
    
    public function doNewsOverTime() {
        $this->basePage("newsovertime.html");
    }
    
    public function doDockerPage() {
        $this->basePage("docker.html");
    }
    
    public function doPostCodePage() {
        $this->basePage("postcoder.html");
    }
    
    public function doGeoMapPage() {
        $this->basePage("geomapping.html");
    }
}
?>