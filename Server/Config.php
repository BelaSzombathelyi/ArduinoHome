<?php

class MYSQLConfiguration {
	public $servername;
	public $username;
	public $password;
	public $database;
}

function getConfiguration() {
	$config = new MYSQLConfiguration();
	$config->servername="localhost";
	return $config;
}

?>