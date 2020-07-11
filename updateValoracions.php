<?php
include 'connect.php';
$exp=$_GET['exp'];
$nivell=$_GET['nivell'];
$area=$_GET['area'];
$any_actual=$_GET['any_actual'];
$tipus_avaluacio=$_GET['tipus_avaluacio'];


//Actualitzam items
for ($i = 1; $i <= 7; $i++) {

	$item=$_GET['item'.$i];
	$query="DELETE FROM valoracions WHERE alumne = ".$exp." AND curs_escolar = ".$any_actual." AND tipus_avaluacio = '".$tipus_avaluacio."'  AND area = '".$area."' AND ambit = 1 AND item = ".$i;
	$mysqli->query($query);
	if ($item == "true") {
		$query="INSERT INTO valoracions (alumne, curs_escolar, tipus_avaluacio, nivell, area, ambit, item, valor) VALUES  ('".$exp."','".$any_actual."','".$tipus_avaluacio."','".$nivell."','".$area."','1','".$i."','x')";
		$mysqli->query($query);
	}
}

//Actualitzam notes
$nota1=$_GET['nota1'];
$nota2=$_GET['nota2'];
$nota3=$_GET['nota3'];

$query='DELETE FROM notes WHERE alumne='.$exp.' AND curs_escolar='.$any_actual.'  AND tipus_avaluacio="'.$tipus_avaluacio.'" AND nivell="'.$nivell.'" AND area="'.$area.'" AND num=1;';
$mysqli->query($query);
$query='DELETE FROM notes WHERE alumne='.$exp.' AND curs_escolar='.$any_actual.'  AND tipus_avaluacio="'.$tipus_avaluacio.'" AND nivell="'.$nivell.'" AND area="'.$area.'" AND num=2;';
$mysqli->query($query);
$query='DELETE FROM notes WHERE alumne='.$exp.' AND curs_escolar='.$any_actual.'  AND tipus_avaluacio="'.$tipus_avaluacio.'" AND nivell="'.$nivell.'" AND area="'.$area.'" AND num=3;';
$mysqli->query($query);


if($nota1!=null){
	$query='INSERT INTO notes VALUES ('.$exp.','.$any_actual.',"'.$tipus_avaluacio.'","'.$nivell.'","'.$area.'",1,'.$nota1.');';
	$mysqli->query($query);
}
if($nota2!=null){
	$query='INSERT INTO notes VALUES ('.$exp.','.$any_actual.',"'.$tipus_avaluacio.'","'.$nivell.'","'.$area.'",2,'.$nota2.');';
	$mysqli->query($query);
}
if($nota3!=null){
	$query='INSERT INTO notes VALUES ('.$exp.','.$any_actual.',"'.$tipus_avaluacio.'","'.$nivell.'","'.$area.'",3,'.$nota3.');';
	$mysqli->query($query);
}



// //Actualitzam Observacions
$observacio=$_GET['observacio'];
$query='DELETE FROM observacions WHERE alumne='.$exp.' AND curs_escolar='.$any_actual.' AND tipus_avaluacio="'.$tipus_avaluacio.'" AND nivell="'.$nivell.'" AND area="'.$area.'";';

echo $query;
echo "\n";
$mysqli->query($query);
$query='INSERT INTO observacions VALUES ('.$exp.','.$any_actual.',"'.$tipus_avaluacio.'","'.$nivell.'","'.$area.'","'.$observacio.'");';
$mysqli->query($query);
echo $query;


$data=array();
$myJSON = json_encode($data);

header('Content-Type: application/json');
echo $myJSON;
//Devolvemos el array pasado a JSON como objeto
?>