<?php

namespace AppSpace\Logging;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MQLogging
{
	private $connection;
	private $queue_zone = "logging_visitor_information";

	public function __construct( $connection ) {
		$this -> connection = $connection;
	}

	public function makeRequest( $data_set ){
		$connection = new AMQPStreamConnection($this->connection->host, $this->connection->port, $this->connection->user, $this->connection->pass);
		$channel = $connection->channel();
		$channel->queue_declare($this->queue_zone, false, false, false, false);
		$msg = new AMQPMessage(json_encode($data_set));
		$channel->basic_publish($msg, '', $this->queue_zone);
		$channel->close();
		$connection->close();
	}
}