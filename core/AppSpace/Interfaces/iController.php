<?php
/**
* Class and Function List:
* Function list:
* - Controller()
* - init()
* - getmodel()
* - template_engine()
* - view()
* Classes list:
*/
namespace AppSpace\Interfaces;

interface iController {
    public function __construct($vars,$settings);
    public function init();
    public function getmodel($model);
    public function template_engine($template_engine);
    public function view($view, $data);
}
?>