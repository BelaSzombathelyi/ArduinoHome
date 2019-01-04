<!DOCTYPE html>
<html>
<head>
	<script>
		function reloadPage() {
  			location.reload();
		}
		function startTimer() {
  			window.setTimeout(reloadPage, 2000);
		}
	</script>
</head>
<body onload="startTimer()">

<?php
/*
*/
include 'Config.php';
include 'MYSQLHelper.php';

$config = getConfiguration();
$connection = createConnection($config);
$sql = "SELECT `type`, `value`, `unit`, `date` FROM `".$config->database."`.`recorded_values` ORDER BY `id` desc LIMIT 1";

$result = $connection->query($sql);

echo "<table border='1'>";
if ($result->num_rows > 0) {
	echo "<tr>";
	echo "<th>Hőmérséklet</th>";
	echo "<th>Dátum</th>";
	echo "</tr>";
    while($row = $result->fetch_assoc()) {
    	echo "<tr>";
		echo "<td>".$row["value"]." °C</td>";
		$dt = new DateTime($row["date"], new DateTimeZone('UTC'));
		$dt->setTimezone(date_default_timezone_get());
		echo "<td>".$dt->format('Y-m-d H:i:s')."</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td>0 results</tr></td>";
}
echo "</table>";
$connection->close();
?>

</body>
</html> 



