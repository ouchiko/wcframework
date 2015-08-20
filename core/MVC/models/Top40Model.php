<?php
/**
 * Class and Function List:
 * Function list:
 * - setEntry()
 * - get()
 * - getTop40()
 * - getRawContent()
 * - parseContent()
 * Classes list:
 * - Top40Strut
 * - Top40Model
 */
namespace MVC\models;

class Top40Strut {
    public $chartEntries = array();
    
    public function setEntry($song_title, $song_artist, $position, $old_position, $weeks_in_chart, $status) {
        $entry = array(
            "song_title" => $song_title,
            "song_artist" => $song_artist,
            "position" => $position,
            "old_position" => $old_position,
            "weeks_in_chart" => $weeks_in_chart,
            "status" => $status
        );
        
        $this->chartEntries[$position] = $entry;
    }
    
    public function get($format) {
        switch ($format) {
            case "json":
                return json_encode($this->chartEntries);
            break;
            case "ser":
                return serialize($this->chartEntries);
            break;
            case "xml" : 
            	$dd = new \DOMDocument();
            	$charts = $dd -> createElement("charts");
            	foreach ( $this -> chartEntries as $chart_item){
            		$entry = $dd -> createElement("entry");
            		foreach ( $chart_item as $name => $value ){
            			$item = $dd -> createElement($name,htmlentities($value, ENT_XML1));
            			$entry -> appendChild($item);
            		}
            		$charts -> appendChild($entry);
            		
            	}
            	$dd -> appendChild($charts);
            	header("content-type: text/xml");
            	return $dd -> saveXML();
            break;
            default:
                return $this->chartEntries;
            break;
        }
    }
}

class Top40Model {
    private $uri = "http://www.bbc.co.uk/radio1/chart/singles/print";
    private $charts = null;
    
    public function getTop40($format) {
        $this->charts = new Top40Strut();
        $t40strut = $this->parseContent($this->getRawContent());
        return $t40strut->get($format);
    }
    
    private function getRawContent() {
    	$test_local = __ROOT__ ."/core/Datafiles/Saved/top40" . date('Y-m-d').".txt";
    	if ( !file_exists($test_local)) {
    		$content = file_get_contents($this->uri);
    		$fp = fopen($test_local,"w");
    		fputs($fp,$content);
    		fclose($fp);
    	}
        return file_get_contents($test_local) ;
    }
    
    private function parseContent($content) {
        
        $counter = 0;
        $points_final = array();
        
        preg_match_all("/<tr>(.*)<\/tr>/Uism", $content, $elements);

        if (is_array($elements[1])) {
            foreach ($elements[1] as $element) {
                $counter++;
                $idx = 0;
                $points_raw = explode("\n", preg_replace("/\t|/", "", strip_tags($element)));
                foreach ($points_raw as $point) {
                    if (trim(chop($point))) {
                        $points_final[$counter][$idx] = trim(chop($point));
                        $idx++;
                    }
                }
            }
            
            if (is_array($points_final)) {
                array_shift($points_final);
                foreach ($points_final as $pf) {
                    @list($position, $status, $previous, $weeks, $artist, $title) = $pf;
                    $this->charts->setEntry($title, $artist, $position, $previous, $weeks, $status);
                }
                
                return $this->charts;
            } 
            else throw new \Exception("Unable to create the final chart set");
        } 
        else throw new \Exception("Unable to process origin data into component elements");
    }
}
