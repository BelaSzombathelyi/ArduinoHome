<?php
include 'Config.php';
include 'MYSQLHelper.php';

$config = getConfiguration();
$connection = createConnection($config);

?>
<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
		function reloadPage() {
  			location.reload();
		}
		function startTimer() {
  			window.setTimeout(reloadPage, 2000);
		}
		google.charts.load('current', {'packages':['corechart']});
      	google.charts.setOnLoadCallback(drawChart);

      	function drawChart() {
        var data = google.visualization.arrayToDataTable([
        	['Dátum', 'Minimum', 'Átlag', 'Maximum'],
<?php
			$sql = file_get_contents('recorded_values_30min.sql');
			$sql = 'SELECT * FROM ('.$sql.') AS `D` LIMIT 72';
			$sql = 'SELECT * FROM ('.$sql.') AS `E` ORDER BY `BY30` ASC';
			$result = $connection->query($sql);
			if ($result->num_rows > 0) {
    			while($row = $result->fetch_assoc()) {
    				echo "['".$row["BY30"]."', ".$row["MIN"].", ".$row["AVG"].", ".$row["MAX"]."],\n";
    			}
			}
?> 
        ]);

        var options = {
          title: 'Hőmérséklet',
          curveType: 'function',
          legend: { position: 'bottom' },
          series: {
          	2: {
          		color: '#FF6666',
          		lineWidth: 1,
          	},
          	1: {
          		color: '#000000',
          		lineWidth: 2,
          	},
          	0: {
          		color: '#3399FF',
          		lineWidth: 1,
          	}
          },

        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
	</script>
</head>
<body onload="startTimer()">
    <div id="curve_chart" style="width: 900px; height: 500px"></div>

<?php
/*
*/
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
		$timeZone = new DateTimeZone("Europe/Budapest");
		$dt->setTimezone($timeZone);
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



