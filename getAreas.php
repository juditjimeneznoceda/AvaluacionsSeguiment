
<?php
include 'connect.php';
$nivell=$_GET['nivell'];
$data=array();
$i=0;
if ($myquery = $mysqli->query('SELECT * FROM arees WHERE nivell="'.$nivell.'";')){  
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