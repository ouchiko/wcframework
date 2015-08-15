<?php
/**
 * Class and Function List:
 * Function list:
 * - init()
 * - find()
 * Classes list:
 * - PostcoderController extends Controller
 */

use AppSpace\Controllers\Controller;
use AppSpace\Data\Mysql;
use MVC\Models\PostcodeModel;

class PostcoderController extends Controller {
    
    private $available_extensions = array(
        "json",
        "html",
        "xml",
        "ser",
        "printr"
    );
    
    private $available_choices = array(
        "travel",
        "streets",
        "postcodes"
    );
    
    /**
     * Default controller method. Provides the documentation view for
     * the postcoder app.
     * @return type
     */
    public function init() {
        $data = array(
            "pagedata" => array(
                "stylesheets" => array(
                    "/css/vendor/prism/prism.css",
                    "/css/fontawesome/css/font-awesome.min.css",
                    "/css/postcoder/documentation.css"
                ) ,
                "js" => array(
                    "/js/vendor/prism/prism.js"
                )
            ) ,
            "vars" => $this->variables->getAll()
        );
        
        $this->view("postcoder/index.html", $data);
    }
    
    /**
     * The API hanlder for the postcoder.  Called via the
     * task method in the routing.
     * @return type
     */
    public function find() {
        
        try {
            
            $db = new Mysql($this->settings->data->mysql->live->host, $this->settings->data->mysql->live->user, $this->settings->data->mysql->live->pass);
            
            /* Determine the extension of the file we've been given */
            $requested_extension = preg_replace("/index\./", "", $this->variables->get("uri.extension"));
            
            /* Check for valid extension */
            if (!in_array($requested_extension, $this->available_extensions)) {
                throw new \Exception("Invalid extension requested.");
            }
            
            /* Check for postcode */
            if (strlen($this->variables->get("uri.postcode")) < 3) {
                throw new \Exception("Postcode requires 3 or more characters to search");
            }
            
            /* Check for choices and ensure they are valid */
            if ($this->variables->get("get.choices")) {
                $choice_options = explode(",", $this->variables->get("get.choices"));
                foreach ($choice_options as $choice_option) {
                    if (!in_array($choice_option, $this->available_choices)) {
                        throw new \Exception("Invalid request by choice. You should include one of the following: " . implode(", ", $this->available_choices));
                    }
                }
            }
            
            $postcodeModel = new PostcodeModel($db, $this->variables->get("uri.postcode") , $requested_extension, $this->variables->get("get.choices"));
            $postcode_data = $postcodeModel->getPostcode();

            if ( !isset($postcode_data) || count($postcode_data) < 1) {
                throw new \Exception("The postcode '".$this->variables->get("uri.postcode")."' you entered brought back no data");
            }
            
            $data = array(
                "pagedata" => array(
                    "extension" => $requested_extension,
                    "sets" => $postcode_data
                ) ,
                "vars" => $this->variables->getAll()
            );
            
            /* Initialise the view */
            $this->view("postcoder/dataview.html", $data);
        }
        catch(\Exception $e) {
            
            /* Define the exception and process */
            $data = array(
                "pagedata" => array(
                    "errors" => array(
                        "exception" => $e->getMessage()
                    ) ,
                    "vars" => $this->variables->getAll()
                )
            );
            
            $this->view("postcoder/dataview.html", $data);
        }
    }
}
?>