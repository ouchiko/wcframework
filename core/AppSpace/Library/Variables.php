<?php
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - getAll()
 * - get()
 * Classes list:
 * - Variables
 */
namespace AppSpace\Library;

class Variables {
    private $vars = null;
    
    /**
     * Process the defined variable set.
     * @return type
     */
    public function __construct($urivars) {
        
        $variable_set = array(
            "get" => $_GET,
            "post" => $_POST,
            "cookie" => $_COOKIE,
            "server" => $_SERVER,
            "env" => $_ENV
        );
        
        $tmp = array();
        
        foreach ($variable_set as $naming => $dataset) {
            foreach ($dataset as $name => $value) {
                $tmp[$naming][$name] = $value;
            }
        }
        
        if (isset($urivars) && $urivars) {
            
            $tmp["uri"] = $urivars;
        }
        
        foreach ($tmp as $coreid => $vars) {
            if (isset($vars) && count($vars) > 0) {
                foreach ($vars as $n => $v) {
                    $this->vars[$coreid][strtolower($n) ] = $v;
                }
            }
        }
    }
    
    /**
     * Returns the entire set, Useful for the controller which will want
     * to pass on the entire var set into Twig.
     * @return type
     */
    public function getAll() {
        return $this->vars;
    }
    
    /**
     * Return the processed variable list at some point
     * @return type
     */
    public function get($reference) {
        $segments = explode(".", $reference);
        if (count($segments) > 1) {
            return isset($this->vars[$segments[0]][$segments[1]]) ? $this->vars[$segments[0]][$segments[1]] : "";
        } 
        else {
            return isset($this->vars[$reference]) ? $this->vars[$reference] : "";
        }
    }
}
?>