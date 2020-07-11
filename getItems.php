<?php
include 'connect.php';

$any_actual=$_GET['any_actual'];
$area=$_GET['area'];

if($area!="tut"){
  $any = $any_actual;
}else{
  $any = 0;
}

$query='SELECT item, nom FROM items WHERE curs_escolar='.$any.' ORDER BY item;';

$data=array();
$i=0;
if ($myquery = $mysqli->query($query)){  

  while($row = $myquery->fetch_assoc()){
    $data[$i]= $row;
    $i++;
  }


}
$myJSON = json_encode($data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);

header('Content-Type: application/json');
echo $myJSON;
//Devolvemos el array pasado a JSON como objeto
?>