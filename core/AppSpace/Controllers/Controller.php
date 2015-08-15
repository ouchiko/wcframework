<?php
/**
* Class and Function List:
* Function list:
* - Controller()
* - template_engine()
* - init()
* - getmodel()
* - view()
* Classes list:
* - Controller
*/
namespace AppSpace\Controllers;

use AppSpace\Interfaces\iController;
use AppSpace\Library\Stash;

class Controller implements iController
{
    private $template_engine = null;
    public $variables = null;
    public $settings = null;
    
    public function __construct($variableObject, $settings)
    {
        $this -> settings = $settings;
        $this->models = new Stash();
        $this -> variables = $variableObject;
    }
    
    public function template_engine($template_engine)
    {
        $this->template_engine = $template_engine;
    }
    
    public function init()
    {
        print "Root Controller - please specify the default init function in your controller file";
    }
    
    public function getmodel($model)
    {
        return new $model;
    }
    
    public function view($view, $data = array())
    {
        if (isset($_GET['vars'])) {
            print "<XMP>";
            print_r($data);
            print "</XMP>";
        }

        try
        {
            echo $this->template_engine->render($view, $data);
        }
        catch ( Exception $e ) {
            echo "There was a problem rendering your page, " . $e -> getMessage();
        }
        
    }
}
?>