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
        
        $this->view("system/" . $view, $data);
    }
    
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