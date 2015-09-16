<?php

	error_reporting(E_ALL);
	ini_set("display_errors","on");
	ini_set("html_errors","on");

	print __DIR__ ;

	require_once __DIR__ . '/../../core/Vendor/autoload.php';
	use PhpAmqpLib\Connection\AMQPStreamConnection;
	use PhpAmqpLib\Message\AMQPMessage;

	$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
	$channel = $connection->channel();

	$channel->queue_declare('logging_visitor_information', false, false, false, false);

	$msg = new AMQPMessage(json_encode($_SERVER));
	$channel->basic_publish($msg, '', 'logging_visitor_information');

	$channel->close();
	$connection->close();

//	print_r($connection);

?>