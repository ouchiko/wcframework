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
        
        /* Define what variables we want to allow into the stack */
        $variable_set = array(
            "get" => $_GET,
            "post" => $_POST,
            "cookie" => $_COOKIE,
            "server" => $_SERVER,
            "env" => $_ENV
        );
        
        $named_variable_array = array();
        
        /* Loop the varables defined the variable_Set and create
         a named array for use. */
        foreach ($variable_set as $naming => $dataset) {
            foreach ($dataset as $name => $value) {
                $named_variable_array[$naming][$name] = $value;
            }
        }
        
        /** URL Variables coming from the request_uri */
        if (isset($urivars) && $urivars) {
            $named_variable_array["uri"] = $urivars;
        }
        
        /** Push these vairables into the var list so they become
         accessible by the rest of the application
         */
        foreach ($named_variable_array as $coreid => $vars) {
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