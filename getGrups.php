<?php
include 'connect.php';
$nivell=$_GET['nivell'];
$any_actual=$_GET['any_actual'];

$query='SELECT grup FROM grups WHERE nivell="'.$nivell.'" and curs_escolar="'.$any_actual.'";';

$data=array();
$i=0;
if ($myquery = $mysqli->query($query)){  

	while($row = $myquery->fetch_assoc()){
		$data[$i]= $row["grup"];
		$i++;
	}


}
$myJSON = json_encode($data);

header('Content-Type: application/json');
echo $myJSON;
//Devolvemos el array pasado a JSON como objeto
?>