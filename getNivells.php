<?php

include 'connect.php';
  

$data=array();
$i=0;
if ($myquery = $mysqli->query("SELECT * FROM nivells")) {
  while($row = $myquery->fetch_assoc()){
    $data[$i]= $row;
    $i++;
  }
}
$myJSON = json_encode($data);

header('Content-Type: application/json');
echo $myJSON;


?>