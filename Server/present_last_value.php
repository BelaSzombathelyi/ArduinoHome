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
		
    var request = new XMLHttpRequest();
    request.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var calculatedData = JSON.parse(this.responseText);
        loadChart(calculatedData);
      }
    };
    request.open("GET", "get_data.php", true);
    request.send();
		
    function loadChart(calculatedData) {
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var dataTable = [['Időszak', 'Minimum', 'Átlag', 'Maximum']];
        calculatedData.reverse().forEach(function(item) {
          var min = item.MIN;
          var avg = item.AVG;
          var max = item.MAX;
          if (min !== null && avg !== null && max !== null) {
            var min = Number.parseFloat(min);
            var avg = Number.parseFloat(avg);
            var max = Number.parseFloat(max);
            dataTable.push([item.interval, min, avg, max]);
          } else {
            dataTable.push([item.interval, null, null, null]);
          }
        });

        var data = google.visualization.arrayToDataTable(dataTable);

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
    }

		
	</script>
</head>
<body">
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



