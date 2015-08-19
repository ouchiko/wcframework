<?php
/**
 * Class and Function List:
 * Function list:
 * - isExtensionOK()
 * - findFile()
 * - calculatePossibleDimensions()
 * - processImage()
 * - sanitizeImageResource()
 * - resize()
 * Classes list:
 * - ImageSizeModel
 */
namespace MVC\Models;

#

class ImageSizeModel {
    private $accepted_extensions = array(
        "png",
        "jpg"
    );
    private $image_pool = "../web/images";
    private $image_reference = null;
    private $logger = null;
    private $fileReference = null;
    private $extension = null;
    private $isValidURI = false;
    private $width = 100;
    private $height = 100;
    
    /**
     * Determine if we have a valid exension set
     * @return type
     */
    private function isExtensionOK() {
        $segments = explode(".", $this->fileReference);
        $extension = $segments[count($segments) - 1];
        $this->extension = $extension;
        return (in_array($extension, $this->accepted_extensions)) ? true : false;
    }
    
    /**
     * Finds the file in the designated zone
     * @param type $target 
     * @return type
     */
    private function findFile($target) {
        if (is_dir($target)) {
            if ($dh = opendir($target)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != ".." && $file != ".") {
                        $this->logger->addInfo("Examining " . $file . " with " . $this->image_reference);
                        if ($file == $this->image_reference) {
                            $this->fileReference = $target . "/" . $this->image_reference;
                            $this->isValidURI = true;
                        }
                        if (is_dir($target . "/" . $file)) {
                            $this->findFile($target . "/" . $file);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Generates the final image
     * @param type $image 
     * @return type
     */
    private function calculatePossibleDimensions($image) {
        $image_ratio = imagesx($image) / imagesy($image);
        
        if ($this->width == "*") {
            $this->width = $this->height * $image_ratio;
            if ($this->width > imagesx($image)) {
                $this->width = imagesx($image);
                $this->height = imagesy($image);
            }
        }
        if ($this->height == "*") {
            $this->height = $this->width / $image_ratio;
            if ($this->height > imagesy($image)) {
                $this->width = imagesx($image);
                $this->height = imagesy($image);
            }
        }
        
        $width_if_height = $this->height * $image_ratio;
        $height_if_width = $this->width / $image_ratio;
        
        if ($width_if_height >= $this->width) {
            $newimage = imagecreatetruecolor($width_if_height, $this->height);
        } 
        else if ($height_if_width >= $this->height) {
            $newimage = imagecreatetruecolor($this->width, $height_if_width);
        }
        
        $destination = imagecreatetruecolor($this->width, $this->height);
        
        imagecopyresized($newimage, $image, 0, 0, 0, 0, imagesx($newimage) , imagesy($newimage) , imagesx($image) , imagesy($image));
        
        imagecopyresized($destination, $newimage, 0, 0, (imagesx($newimage) / 2) - ($this->width / 2) , (imagesy($newimage) / 2) - ($this->height / 2) , $this->width, $this->height, $this->width, $this->height);
        
        return $destination;
    }
    
    /**
     * process the image reqest
     * @return type
     */
    private function processImage() {
        if (file_exists($this->fileReference) && $this->width && $this->height) {
            
            switch ($this->extension) {
                case "png":
                    $loader = "imagecreatefrompng";
                break;
                case "jpg":
                    $loader = "imagecreatefromjpeg";
                break;
            }
            if ($loader) {
                $this->logger->addInfo("Loading in " . $this->fileReference);
                $image = $loader($this->fileReference);
                
                $star_dimensions = ($this->width == "*" || $this->height == "*") ? true : false;
                
                if ((imagesx($image) < $this->width || imagesy($image) < $this->height) && !$star_dimensions) {
                    if ($this->width != "*" && $this->height == "*") throw new \Exception("Width and height of master image must be more than required width and height [" . imagesx($image) . "] [" . imagesy($image) . "]");
                }
                
                $image = $this->calculatePossibleDimensions($image);
                
                header("content-type: image/jpeg");
                imagejpeg($image);
                exit;
            }
        }
    }
    
    /**
     * image sanity checks
     * @return type
     */
    private function sanitizeImageResource() {
        
        if (isset($this->image_reference)) {
            
            $this->findFile($this->image_pool);
            
            if ($this->isValidURI && $this->fileReference) {
                if ($this->isExtensionOK()) {
                    $this->logger->addInfo("Located valid reference file");
                } 
                else {
                    $this->logger->addInfo("Extension doesnt meet criteria");
                    throw new \Exception("Extension is invalid");
                }
            } 
            else {
                $this->logger->addInfo("Invalid image reference");
                throw new \Exception("Reference is invalid");
            }
        } 
        else {
            $this->logger->addInfo("No reference given");
            throw new \Exception("Reference is undefined");
        }
    }
    
    /**
     * resizer function
     * @param type $reference 
     * @param type $width 
     * @param type $height 
     * @return type
     */
    public function resize($reference, $width, $height) {
        global $logger;
        $this->logger = $logger;
        $this->logger->addInfo(sprintf("Opening image resize attempt with %s", $reference));
        $this->image_reference = $reference;
        $this->width = $width;
        $this->height = $height;
        $this->sanitizeImageResource();
        $this->processImage();
    }
}
?>