<?php



	/** BASIC SETUP **/
	include_once "Setup/definitions.php";
	include_once "Setup/twig_setup.php";
	include_once "Setup/autoload.php";
	
	/** USAGE **/
	use AppSpace\Controllers\Controller;
	use AppSpace\Library\ConfigurationLoader;
	use AppSpace\Library\RoutingTable;

	/** Settings for the system **/
	$settings = ConfigurationLoader::load(__CONFIGURATION__,"settings");

	/** PREPARE THE ROUTE IN **/
	$route = RoutingTable::parse( 
		ConfigurationLoader::load(__CONFIGURATION__, "routing"),
		$settings
	);

	/** SEND TWIG AND RUN THE CONTROLLER **/
	$route -> controllerObject -> template_engine($twig);
	$route -> controllerObject -> {$route->controllerMethod}();

	/** OUR JOB IS DONE **/
?>