<?php
/**
* Class and Function List:
* Function list:
* - createController()
* - lookForTask()
* - parse()
* Classes list:
*/
namespace AppSpace\Interfaces;


interface iRoutingTable {
    public static function createController($controller, $vars, $settings);
    public static function lookForTask($urlStack, $route);
    public static function parse($routing_set, $settings);
}
?>