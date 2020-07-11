<?php
include 'connect.php';
$data=array();
$i=0;
if ($myquery = $mysqli->query("SELECT curs_escolar, tipus_avaluacio FROM curs_escolar order by curs_escolar desc")) {
  while($row = $myquery->fetch_assoc()){
    $data[$i]= $row;
    $i++;
  }
}
$myJSON = json_encode($data);

header('Content-Type: application/json');
echo $myJSON;
?>