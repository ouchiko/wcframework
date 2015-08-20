<?php
/**
 * Class and Function List:
 * Function list:
 * - createController()
 * - lookForTask()
 * - parse()
 * Classes list:
 * - RoutingTable
 */
namespace AppSpace\Library;

use \Exception;
use AppSpace\Interfaces\iRoutingTable;
use AppSpace\Library\UrlStack;
use AppSpace\Library\Variables;
use AppSpace\Exceptions\RoutingException;

class RoutingTable implements iRoutingTable
{
    
    /** Route method **/
    public static $default_root_method = "init";
    
    /**
     * Creates the new controller and passes the variable through to the controller.
     * @param type $controller
     * @return types
     */
    public static function createController($controller, $urivars, $settings) {
        return new $controller(new Variables($urivars), $settings);
    }
    
    /**
     * Examine the route stack for a possible task url.
     * @param type $urlStack
     * @param type $route
     * @return type
     */
    public static function lookForTask($urlStack, $route) {
        global $logger;
        /* Look into the possible tasks in the routing configuration. If we find one then
        we apply the method for use within the controller.  e.g, if the url ends with
        create and a defined task for create exists we route into the controller(create)#
        method */
        if (isset($route->tasks)) {
            foreach ($route->tasks as $routeTaskName => $routeTaskMethod) {
                if ($urlStack->last_task && $urlStack->last_task == $routeTaskName) {
                    $logger->addInfo(sprintf("Task located in routing: %s", $routeTaskMethod));
                    return $routeTaskMethod;
                }
            }
        }
        
        /** If we dont find any tasks then we want set a default method to run. All
         Controllers work with init method by default *
         */
        return isset($route->controllerMethod) ? $route->controllerMethod : self::$default_root_method;
    }

    public static function doRouteLogging( $route , $testuri){
        global $logger;
        // You can now use your logger
        $logger->addInfo(sprintf('Routing Test: %s',$testuri));
        $logger->addInfo(sprintf('Routing controller: %s',$route -> controller));
        $logger->addInfo(sprintf('Routing method: %s',$route -> controllerMethod));
    }

    /**
     * Looks for variable elements within the URL
     * @param type $route 
     * @return type
     */
    public static function lookForVariables( $route, $uri )
    {
        // print "Requested URI: " . $uri . "<BR>";
        // print "Route Variables: " . $route -> variables . "<BR>";
        // print "Route Seperator: " . $route -> seperator . "<BR>";
        // print "Route Naming: " . $route -> naming . "<BR>";

        $uncaptured_variable_value = "a" ; 

        if ( isset($route -> seperator) && isset($route -> naming)){

            $elements = explode($route->seperator, preg_replace("/^\/|\/$/","",$uri));

            $element_naming = explode(",", $route -> naming);

            foreach ( $elements as $idx => $value ){
                if ( !isset($element_naming[$idx])) {
                    $element_naming[$idx] = "var_" . $uncaptured_variable_value;
                    $uncaptured_variable_value++;   
                }
                $variable_Set[$element_naming[$idx]] = $value;
            }
        }

        return isset($variable_Set) ? $variable_Set : false;;

    }
    
    /**
     * Parse the routing set.  This will iterate through all the available URLs and
     * proceed to use one when it's found.  First come, first served.
     * @param type $routing_set
     * @return type
     */
    public static function parse($routing_set, $settings) {

        try {
        
            if ( $routing_set ){
                /** Trim the URI down to it's minimum form **/
                $test_scope = trim(chop(preg_replace("/\?(.*)/", "", $_SERVER['REQUEST_URI'])));
                
                /** Process the URL into component elements **/
                $urlStack = new UrlStack($test_scope);
                
                /** Cycle the routes and examine them for a match **/
                foreach ($routing_set->routing as $route) {
                    $GLOBALS['logger'] -> addInfo(sprintf("Examing possible route: %s / %s", $route->uri, $test_scope));
                    /** Remove any variable entries so we can match the URL **/
                    if (preg_match("/" . str_replace('/', '\/', $route->uri) . "/i", $test_scope)) {
                        if ( preg_match("/::/",$route->controller)){
                            $segments = explode("::", $route -> controller);
                            $route -> controller = $segments[0];
                            $route -> controllerMethod = $segments[1];
                        }
                        /* We have a match */
                        $route->urivars = self::lookForVariables($route, $test_scope);
                        $route->controllerMethod = self::lookForTask($urlStack, $route);
                        $route->controllerObjectReference = $route->controller;
                        $route->controllerObject = self::createController($route->controllerObjectReference, $route->urivars, $settings);
                        self::doRouteLogging( $route ,str_replace('/', '\/', $route->uri));
                        return $route;
                    }
                }
                
                /** Since we havent returned a route then we return the base error **/
                $route = $routing_set->errors->base_error;
                $route->controllerMethod = "init";
                $route->controllerObjectReference = $route->controller;
                $route->controllerObject = self::createController($route->controllerObjectReference,null, $settings);
                self::doRouteLogging( $route, "error" );
                return $route;

            }

            throw new \Exception("NO_CONFIGURATION_ROUTING_FILE");
        } 
        catch ( Exception $e ) {
            echo "Exception: " . $e -> getMessage();
            exit;
        }
    }
}
?>