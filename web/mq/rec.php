<?php
	
	require_once __DIR__ . '/../../core/Vendor/autoload.php';
	use PhpAmqpLib\Connection\AMQPStreamConnection;

	$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
	$channel = $connection->channel();

	$channel->queue_declare('logging_visitor_information', false, false, false, false);

	echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

	$callback = function($msg) {
	  echo " [x] Received ", $msg->body, "\n";
	};

	$channel->basic_consume('logging_visitor_information', '', false, true, false, false, $callback);

	while(count($channel->callbacks)) {
	    $channel->wait();
	}
?>