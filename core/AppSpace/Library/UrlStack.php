<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * Classes list:
 * - UrlStack
 */
namespace AppSpace\Library;

class UrlStack
{
    public $parts = array();
    public $last_task = null;
    
    /**
     * Process the URL into segments and the ending (possible) task element
     * @param type $url 
     * @return type
     */
    public function __construct($url)
    {
        $url_exploded_segments = explode("/", $url);
        
        foreach ($url_exploded_segments as $url_segment_item) {
            $this->parts[] = $url_segment_item;
        }
        
        $this->last_task = $this->parts[count($this->parts) - 1];
    }
}
?>