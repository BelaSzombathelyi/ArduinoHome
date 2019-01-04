<?php

function createConnection($config) {
	$connection = new mysqli($config->servername, $config->username, $config->password);
	if ($connection->connect_error) {
    	die("Connection failed: " . $connection->connect_error);
	} 
	return $connection;
}

?>