<?php

	/**
	 * Framework System
	 * This is the root caller for the framework.
	 */

	/** BASIC SETUP **/
	include_once "Setup/definitions.php";
	include_once "Setup/twig_setup.php";
	include_once "Setup/autoload.php";
	include_once "Vendor/autoload.php";
	
	/** USAGE **/
	use AppSpace\Controllers\Controller;
	use AppSpace\Library\ConfigurationLoader;
	use AppSpace\Library\RoutingTable;
	use AppSpace\Logging\MQLogging;
	use Monolog\Logger;
	use Monolog\Handler\StreamHandler;
	use Monolog\Handler\FirePHPHandler;

	// Create the logger
	$logger = new Logger('my_logger');
	// Now add some handlers
	$logger->pushHandler(new StreamHandler(__LOGS__.'/appcore.log', Logger::DEBUG));
	$logger->pushHandler(new FirePHPHandler());

	// You can now use your logger
	$logger->addInfo('Initialising new request for page');

	$GLOBALS['logger'] = $logger;

	/** Settings for the system **/
	$settings = ConfigurationLoader::load(__CONFIGURATION__,"settings");

	$visitorLogging = new MQLogging($settings->rabbitmq->primary);
	$visitorLogging -> makeRequest(array("timing" => time(), "details" => $_SERVER, "get" => $_GET, "post" => $_POST, "cookie" => $_COOKIE));

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