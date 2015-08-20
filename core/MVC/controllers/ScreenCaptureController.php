<?php
/**
* Class and Function List:
* Function list:
* - init()
* Classes list:
* - ScreenCaptureController extends Controller
*/
use AppSpace\Controllers\Controller;
use MVC\models\ScreenCaptureModel;

class ScreenCaptureController extends Controller {
    
    /**
     * Runs the API documentation for the stuff
     * @return type
     */
	public function apiDocs(){
		$data = array(
            "pagedata" => array(
                "apidocs" => true
            ) ,
            "vars" => $this->variables->getAll()
        );
		$this->view("system/capture.html", $data);
	}

	public function init(){
		$data = array(
            "pagedata" => array(
                "intropage" => true
            ) ,
            "vars" => $this->variables->getAll()
        );
		$this->view("system/capture.html", $data);
	}

    /**
     * Controller initialiser
     * @return type
     */
    public function requestImage() {
        
        try {            
            /* Model Execution */
            $screen_capture_model = new ScreenCaptureModel();
            $screen_capture_model->loadStyleSheet(__ROOT__ . "/core/Datafiles/PhantomJS/raster.js");
            $screen_capture_model->processVariables($this->variables);
            $image_output = $screen_capture_model->doScreenShot($screen_capture_model->getNode());
            /* Image output */
            if ( isset($image_output)) {
            	header("content-type: image/jpg");
           		imagejpeg($image_output);
            } else {
            	throw new \Exception("Unable to locate image output");
            }
           
        }
        catch(Exception $exception) {
            $GLOBALS['logger']->addInfo("Unable to process screen shot request : " . $exception->getMessage());
            $data = array(
                "pagedata" => array(
                    "exception" => $exception->getMessage()
                ) ,
                "vars" => $this->variables->getAll()
            );
            $this->view("system/capture.html", $data);
        }
        
        /* No view here, this is direct to the gfx */
    }
}
?>