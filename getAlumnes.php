<?php
include 'connect.php';

$any_actual=$_GET['any_actual'];
$nivell=$_GET['nivell'];
$grup=$_GET['grup'];

$query='SELECT exp, nom, llinatge1 FROM alumnes al JOIN alumne_any an ON al.exp=an.alumne WHERE curs_escolar='.$any_actual.' AND nivell="'.$nivell.'" AND grup="'.$grup.'" ORDER BY llinatge1,llinatge2,nom;';

$data=array();
$i=0;
if ($myquery = $mysqli->query($query)){  

  while($row = $myquery->fetch_assoc()){
    $data[$i]= $row;
    $i++;
  }


}
$myJSON = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);

header('Content-Type: application/json');
echo $myJSON;
//Devolvemos el array pasado a JSON como objeto
?>