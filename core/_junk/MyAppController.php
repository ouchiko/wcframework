<?php
/**
* Class and Function List:
* Function list:
* - init()
* - create()
* - doWankSystem()
* Classes list:
* - MyAppController extends Controller
*/
	
use AppSpace\Controllers\Controller;
use MVC\models;

class MyAppController extends Controller
{
    public function init()
    {
        print "MyAppController is running";
        print "Alpha Bravi Niner";
    }
    
    public function create()
    {
        print "We are printing out the create method here";
    }
    
    public function doWankSystem()
    {

    	
        
        $model = new TestModel();
        $data = $model->getData();
        print "Wank System " . print_r($data,true);
    }
}
?>