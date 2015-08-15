<?php
	/** Twig **/
	require_once __ROOT__."/core/vendor/Twig/lib/Twig/Autoloader.php";
	Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem(__ROOT__."/core/MVC/views/");
	$twig = new Twig_Environment($loader, array(
	    //'cache' => __ROOT__."/../cache",
	));
	$twig->addExtension(new Twig_Extension_Debug());
?>
