<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - __construct()
 * - loadStyleSheet()
 * - processVariables()
 * - getNode()
 * - doScreenShot()
 * Classes list:
 * - ScreenCaptureNode
 * - ScreenCaptureModel
 */

/**
 * Models > ScreenCaptureNode
 * Houses the screen capture data set
 */

namespace MVC\Models;

class ScreenCaptureNode {
    public $uri;
    public $format;
    public $width;
    public $content;
    public $stylesheet;
    public $destination;
    public function __construct() {
        $this->destination = "/tmp/" . md5(uniqid(0, 99)) . ".jpg";
    }
}

/**
 * Models > ScreenCaptureModel
 * Runs the process of capturing a remote URI into an image
 */

class ScreenCaptureModel {
    
    private $formats = array(
        "png",
        "jpg",
        "jpeg"
    );
    private $phantom = "/usr/bin/phantomjs";
    private $screen_capture_block = null;
    private $logger = null;
    
    /**
     * Initialise and set the capture cblock as a ScreenCaptureNode
     * @return type
     */
    public function __construct() {
        global $logger;
        $this->logger = $logger;
        $this->screen_capture_block = new ScreenCaptureNode();
    }
    
    /**
     * Load in the required phantom JS style sheet we want to use
     * @param type $sheet_file
     * @return type
     */
    public function loadStyleSheet($sheet_file) {
        $this->screen_capture_block->stylesheet = $sheet_file;
    }
    
    /**
     * Process the incoming variables and sanity check the input
     * @param type $vars
     * @return type
     */
    public function processVariables($vars) {
        if ($vars->get("get.uri") && preg_match("/^http|^https/i", $vars->get("get.uri"))) {
        	//print $vars -> get("get.width");
            if ($vars->get("get.width")=="*"||is_numeric($vars->get("get.width"))) {
                if ($vars->get("get.format") && in_array($vars->get("get.format") , $this->formats)) {
                    $this->screen_capture_block->uri = $vars->get("get.uri");
                    $this->screen_capture_block->format = $vars->get("get.format");
                    $this->screen_capture_block->width = $vars->get("get.width");
                } 
                else throw new \Exception("Invalid format: " . $vars ->get("get.format"));
            } 
            else throw new \Exception("No width provided:" . $vars -> get("get.width"));
        } 
        else throw new \Exception("Invalid URI: " . $vars->get("get.uri"));
    }
    
    /**
     * Returns the ScreenCaptureNode to the caller
     * @return type
     */
    public function getNode() {
        return $this->screen_capture_block;
    }
    
    /**
     * Carry out the screen shot request
     * @param type ScreenCaptureNode $node
     * @return type
     */
    public function doScreenShot(ScreenCaptureNode $node) {
        $this->logger->addInfo("Processing screen shot request");
        
        $phantom_uri = sprintf("%s --ssl-protocol=any --ignore-ssl-errors=true %s \"%s\" %s \"entire page\" 1", $this->phantom, $node->stylesheet, $node->uri, $node->destination);
        /* Executes the phantom request */
        exec($phantom_uri);
        
        if (file_exists($node->destination)) {
            $png = imagecreatefromjpeg($node->destination);
            if ( $node->width == "*" ) return $png;
            
            $ratio = imagesx($png) / imagesy($png);
            $height = $node->width / $ratio;
            
            $newpng = imagecreatetruecolor($node->width, $height);            
            imagealphablending($newpng, true);
            
            imagecopyresized($newpng, $png, 0, 0, 0, 0, $node->width, $height, imagesx($png) , imagesy($png));
            
            unlink($node->destination);
            
            return $newpng;
        } 
        else {
            throw new \Exception("Failed to obtain the specified URL");
        }
        
        /* Process the ScreenCaptureNode */
    }
}

