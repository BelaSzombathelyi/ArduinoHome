<?php
include 'Config.php';
include 'MYSQLHelper.php';
header('Content-Type: application/json');

$config = getConfiguration();
$connection = createConnection($config);


function getIntervals($connection) {
    $sql = "CALL `id8378391_test_database`.`GetDateListThirtyMin`";
    $result = $connection->query($sql);
    $intervalArray = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $item = (object) [
            'from' => $row["from_date"],
            'to' =>  $row["to_date"]
          ];
          array_push($intervalArray, $item);
        }
    }
    return $intervalArray;
}


function getValues($connection) {
  $sql = file_get_contents('recorded_values_30min.sql');
  $sql = 'SELECT * FROM ('.$sql.') AS `D` LIMIT 72';
  $sql = 'SELECT * FROM ('.$sql.') AS `E` ORDER BY `BY30` DESC';
  $valuesArray = array();
  $result = $connection->query($sql);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $item = (object) [
        'BY30' => $row["BY30"],
        'MIN' =>  $row["MIN"],
        'AVG' =>  $row["AVG"],
        'MAX' =>  $row["MAX"]
      ];
      array_push($valuesArray, $item);
    }
  }
  return $valuesArray;
}

function calculateValuesByIntervalArray($values, $intervals) {
  $array = array();
  $j=0;
  foreach ($intervals as $i => $interval) {
    $interval = json_decode(json_encode($interval), true);
    $fromDate = $interval["from"];
    $toDate = $interval['to'];
    $added=false;
    if ($j < count($values)) {
      $value=$values[$j];
      $value = json_decode(json_encode($value), true);
      $valueDateIndex=$value["BY30"];
      $valueDatePrefix=substr($valueDateIndex, 0, 13);  // "2019-01-03 20_1"
      $fromPrefix=substr($fromDate, 0, 13);             // "2019-02-16 17:30:00"

      if ($valueDatePrefix === $fromPrefix) {
        //"0" or "1"
        $valueDateKey=substr($valueDateIndex, 14, 1); // "2019-01-03 20_1"
        //"0" or "3"
        $fromKey=substr($fromDate, 14, 1);            // "2019-02-16 17:30:00"
        // Make compare easier
        if ($fromKey === "3") {
          $fromKey="1";
        }

        if ($fromKey === $valueDateKey) {
          $item = (object) [
          'interval' => $fromDate." - ".$toDate,
          'MIN' =>  $value['MIN'],
          'AVG' =>  $value['AVG'],
          'MAX' =>  $value['MAX']
        ];
        array_push($array, $item);
          $added=true;
          $j = $j + 1;
        }
      }

    }
    if(!$added) {
      $item = (object) [
        'interval' => $fromDate." - ".$toDate,
        'MIN' =>  NULL,
        'AVG' =>  NULL,
        'MAX' =>  NULL
      ];
      array_push($array, $item);
    }
  }
  return $array;
}

$values = getValues($connection);
//echo json_encode($values, JSON_PRETTY_PRINT);

$intervals = getIntervals($connection);
//echo json_encode($intervals, JSON_PRETTY_PRINT);

$result = calculateValuesByIntervalArray($values, $intervals);
echo json_encode($result, JSON_PRETTY_PRINT);

$connection->close();
?>

