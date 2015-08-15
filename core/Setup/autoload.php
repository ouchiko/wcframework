<?php
	require_once(__ROOT__ . "/core/Vendor/autoload.php");
	/** Autoloader **/
	spl_autoload_register(function ($class) {	
		$path_options = array(
			__ROOT__ . "/core/" .preg_replace("/\\\/","/",$class) . '.php',
			__ROOT__ . "/core/Vendor/Twig/lib/" .preg_replace("/\\\/","/",$class) . '.php',
			//__ROOT__ . "/core/Vendor/monolog/monolog/src/" .preg_replace("/\\\/","/",$class) . '.php',
			__ROOT__ . "/core/MVC/controllers/" .preg_replace("/\\\/","/",$class) . '.php',
			__ROOT__ . "/core/MVC/models/" .preg_replace("/\\\/","/",$class) . '.php'
		);
		foreach ($path_options as $path_option)
		{
			if ( isset($_GET['showloader']) ) print "Loading $path_option (".file_exists($path_option).")<BR>";
			if ( file_exists($path_option)){
				require_once $path_option;    
				break;
			}
		}
	});
?>
