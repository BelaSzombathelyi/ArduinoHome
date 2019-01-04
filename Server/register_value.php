<?php

/*
*/
include 'Config.php';
include 'MYSQLHelper.php';

$config = getConfiguration();

$type = $_GET['type'];
$unit = $_GET['unit'];
$value = floatval($_GET['value']);

$sql = "INSERT INTO `".$config->database."`.`recorded_values` (`type`, `unit`, `value`) VALUES ('".$type."', '".$unit."', ".$value.")";
$connection = createConnection($config);
if ($connection->query($sql) === TRUE) {
	echo "OK";
} else {
	echo "Error: ".$sql."\n".$connection->error;
}
$connection->close();

?>