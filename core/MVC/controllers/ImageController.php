<?php
/**
 * Class and Function List:
 * Function list:
 * - init()
 * Classes list:
 * - ImageController extends Controller
 */

use AppSpace\Controllers\Controller;
use MVC\models\ImageSizeModel;

class ImageController extends Controller {
    
    /**
     * Controller initialiser
     * @return type
     */
    public function init() {
        try {
            $imageSizer = new ImageSizeModel();
            $imageSizer->resize($this->variables->get("uri.reference") , $this->variables->get("get.width") , $this->variables->get("get.height"));
        }
        catch(Exception $e) {
            print "Exception in : " . $e->getMessage();
        }
    }
}
?>