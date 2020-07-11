<?php
include 'connect.php';

$tipus_avaluacio=$_GET['tipus_avaluacio'];
$any_actual=$_GET['any_actual'];
$nivell=$_GET['nivell'];
$area=$_GET['area'];

$query='SELECT alumne, item, valor FROM valoracions WHERE tipus_avaluacio="'.$tipus_avaluacio.'" and curs_escolar='.$any_actual.' and nivell="'.$nivell.'" and area="'.$area.'" order by alumne;';

$data=array();
$i=0;
if ($myquery = $mysqli->query($query)){  

  while($row = $myquery->fetch_assoc()){
    $data[$i]= $row;
    $i++;
  }


}
$myJSON = json_encode($data);

header('Content-Type: application/json');
echo $myJSON;
//Devolvemos el array pasado a JSON como objeto
?>