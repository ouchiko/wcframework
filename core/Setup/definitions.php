<?php
	/** Definitions **/
	define("__CONFIGURATION__", "../configuration");
	define("__VIEWS__", "../MVC/views");
	define("__MODELS__","../MVC/models");
	define("__CONTROLLERS__","../MVC/controllers");
	define("__VENDOR__","../Vendor");
	define("__ERROR_VISUAL__", true);
	define("__ROOT__" , $_SERVER['DOCUMENT_ROOT']);
	define("__LOGS__", "../logging");

	if ( __ERROR_VISUAL__ ) 
	{
		error_reporting( E_ALL );
		ini_set( "display_errors", "on" );
		ini_set("html_errors","on");
		ini_set("display_startup_errors", "on");
	}
?>	