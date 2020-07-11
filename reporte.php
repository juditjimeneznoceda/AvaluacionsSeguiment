<?php

session_start();
include 'connect.php';

$nivell=$_GET["nivell"];
$grup=$_GET["grup"];
$num_alumnes=$_GET["num_alumnes"];
$exp=$_GET["exp"];
$tipus_avaluacio=$_GET["tipus_avaluacio"];
$any_actual=$_GET['any_actual'];

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>Avaluació <?php echo $tipus_avaluacio.". ".$any_actual."-".($any_actual+1);  ?></title>
	<style>
		body{
			margin: 10px;
			font-size:10px;
		}
		p{
			margin: 10px;
			text-align: left;
			font-size:12px;
		}
		table {
			border-collapse: collapse;
			margin:auto;
		}

		table, th, td {
			border: 1px solid black;
			padding: 5px;
		}
		.sinborde{
			border: 0px;
		}

		.sinborde th, .sinborde td {
			border: 0px;
			padding: 5px;
			margin:20px;
		}
	</style>

</head>
<body onload="window.print()">

<?php
  if($num_alumnes=="tots"){
	  // llistam tots els alumnes del grup
	  $alumnes=$mysqli->query("SELECT * FROM alumne_any where curs_escolar='$any_actual' and nivell='$nivell' and grup='$grup'");
	}else{
	  //llistam nomes un alumne
	  $alumnes=$mysqli->query("SELECT * FROM alumne_any where curs_escolar='$any_actual' and nivell='$nivell' and grup='$grup' and alumne='$exp'");
	}


if (!$alumnes) {
	echo 'ERROR: ' . mysql_error(); 
	exit;
}else {
	while($alu = $alumnes->fetch_assoc()){
		$exp=$alu['alumne'];
		
?>
		<div style="border:1px solid black; width:700px;height:1025px;">
		<table style="border:0px;">
		<tr style="border:0px;">

		<td style="width: 10%;border:0px;"><img src="images/logoiessineunegre.png" width="100%" /></td>
		<td style="width: 90%;border:0px;">
		<p>IES Sineu<br/>
		Carretera de Lloret de Vistalegre s/n, 07510 - Sineu<br/>
		Tel: 971520268/971855127 - fax: 971855023<br/>
		iessineu@educaib.eu<br/>
		www.iessineu.net<br/>
		</p>
		</td>
		</tr>
		</table>


		<?php
		$res = $mysqli->query("SELECT llinatge1,llinatge2,nom FROM alumnes where exp='$exp'"); 
		$nom="";
		if (!$res) {
			echo 'ERROR: ' . mysql_error();
			exit();
		}else{//posam el nom a un string 
			$row=$res->fetch_assoc();
			$nom=$row["llinatge1"]." ".$row["llinatge2"].", ".$row["nom"];
		}

		?>
		<p style="font-size:12px;">Benvolguda Família:</p>
		<p style="font-size:12px;">Us comunicam que l’equip de professors/es del grup <?php echo $nivell.' '.$grup;?> 
		ens hem reunit en sessió d’<b>Avaluació <?php echo $tipus_avaluacio;?></b>, i valoram que el vostre fill/a <?php echo $nom;?></p>

		<?php

		echo '<table>';
		echo '<th>Valoracions</th>';
		$ObservacionsTutoria = "";
		$resultArees = $mysqli->query("SELECT nom, area FROM arees where nivell='$nivell' order by nom"); 
		if (!$resultArees) {
			echo 'ERROR: ' . mysql_error(); 
			exit;
		}else{
			while($row=$resultArees->fetch_assoc()){
				//Posam totes les àrees menys la de tutoria.
				if($row['area']!='tut') echo '<th>'.$row['area'].'</th>';
			}
		}			


		//RECUPERAM ELS ITEMS DEL CURS ESCOLAR introduit
		$resultItems = $mysqli->query("SELECT nom, item FROM items where curs_escolar='$any_actual'order by ambit,item"); 
		if (!$resultItems) {
			echo 'ERROR: ' . mysql_error();
			exit;
		}else{
			$i=0;
			while($row=$resultItems->fetch_assoc()){	
				$i++;
				echo '<tr>';
				echo '<td>'.$row["nom"].'</td>';
				$item = $row["item"];
				$resultArees = $mysqli->query("SELECT nom, area FROM arees where nivell='$nivell' order by nom"); 
				if (!$resultArees) {
					echo 'ERROR: ' . mysql_error(); 
					exit;
				}else{
					while($row=$resultArees->fetch_assoc()){
						$marca = 0;
						$area=$row['area'];
						//Posam totes les àrees menys la de tutoria.
						if($row['area']!='tut') {
							$valoracions = $mysqli->query("SELECT valor FROM valoracions where alumne='$exp' and curs_escolar='$any_actual' and tipus_avaluacio='$tipus_avaluacio' and nivell='$nivell' and area='$area' and item='$item' and ambit='1'"); 
							if (!$valoracions) {
								echo 'ERROR: ' . mysql_error(); 
								exit;
							}else{
								while($row=$valoracions->fetch_assoc()){
									echo '<td style="text-align:center;font-family: sans-serif;">'.$row['valor'].'</td>';
									$marca=1;
								}
							}
							if($marca == 0){
								echo '<td></td>';
							}
						}
				
					}
				}		

				echo '</tr>';
			}
		}

		echo '</table>';

		echo '<br/>';
		echo '<br/>';
		echo '<table width="100%">';
		echo '<th width="6%">Àrea</th>';
		echo '<th width="20%">Nom àrea</th>';
		echo '<th width="6%">Nota 1</th>';
		echo '<th width="6%">Nota 2</th>';
		echo '<th width="6%">Nota 3</th>';
		echo '<th>Observacions</th>';



		$arees = $mysqli->query("SELECT nom, area FROM arees where nivell='$nivell' order by nom");
		if (!$arees) {
			echo 'ERROR: ' . mysql_error(); 
			exit;
		}else{
			while($rowArees=$arees->fetch_assoc()){
				$area= $rowArees['area'];
				if($area!='tut'){
					echo '<tr>';
					echo '<td>'.$rowArees["area"].'</td>';
					echo '<td>'.$rowArees["nom"].'</td>';
		
		
					$notes = $mysqli->query("SELECT num, nota FROM notes where alumne='".$exp."' and nivell='".$nivell."' and area='".$area."' and curs_escolar='".$any_actual."' and tipus_avaluacio='".$tipus_avaluacio."' order by num");
					if (!$notes) {
						echo 'ERROR: ' . mysql_error();
						exit;
					}else{
						//Mostram les notes		
						for($i=1;$i<=3;$i++){
							$rowNotes=$notes->fetch_assoc();
							echo '<td>'.$rowNotes['nota'].'</td>';
						}
					}
		
					$observacions = $mysqli->query("SELECT * FROM observacions where curs_escolar='".$any_actual."' and tipus_avaluacio='".$tipus_avaluacio."' and  alumne='".$exp."' and nivell='".$nivell."' and area='".$area."' "); 
					$rowObservacions=$observacions->fetch_assoc();
		
					echo '<td>'.$rowObservacions["observacio"].'</td>';
					echo '</tr>';
				}else{
					$observacions = $mysqli->query("SELECT * FROM observacions where tipus_avaluacio='".$tipus_avaluacio."' and  alumne='".$exp."' and nivell='".$nivell."' and area='".$area."'"); 
					$rowObservacions=$observacions->fetch_assoc();
		
					$observacionsTutoria=$rowObservacions["observacio"];
				}
			}		
		}
		echo '</table>';
		echo '<br/>';
		echo '<br/>';

		$observacionsFinals='<table><th>Observacions de l\'equip educatiu i/o del tutor/a</th><tr><td>';
		$contingutObservacions='';
		
		$valoracions = $mysqli->query("SELECT valor FROM valoracions where alumne='$exp' and tipus_avaluacio='".$tipus_avaluacio."' and area='tut' and item=1 and ambit=1"); 
		if (!$valoracions) {
			echo 'ERROR: ' . mysql_error(); 
			exit;
		}else{
			while($row=$valoracions->fetch_assoc()){
				$resultItemsTutoria = $mysqli->query("SELECT nom FROM items where curs_escolar=0 and item=1"); 
				if (!$resultItemsTutoria) {
					echo 'ERROR: ' . mysql_error();
					exit;
				}else{
					$i=0;
					while($row=$resultItemsTutoria->fetch_assoc()){	
						$i++;
						$contingutObservacions=$contingutObservacions.'<b>'.$row["nom"].'</b> ';
					}
				}
			}
		}
		
		$valoracions = $mysqli->query("SELECT valor FROM valoracions where alumne='$exp' and tipus_avaluacio='".$tipus_avaluacio."' and area='tut' and item=2 and ambit=1"); 
		if (!$valoracions) {
			echo 'ERROR: ' . mysql_error(); 
			exit;
		}else{
			while($row=$valoracions->fetch_assoc()){
				$resultItemsTutoria = $mysqli->query("SELECT nom FROM items where curs_escolar=0 and item=2"); 
				if (!$resultItemsTutoria) {
					echo 'ERROR: ' . mysql_error();
					exit;
				}else{
					$i=0;
					while($row=$resultItemsTutoria->fetch_assoc()){	
						$i++;
						$contingutObservacions=$contingutObservacions.'<b>'.$row["nom"].'</b> ';
					}
				}
			}
		}
		
		$valoracions = $mysqli->query("SELECT valor FROM valoracions where alumne='$exp' and tipus_avaluacio='".$tipus_avaluacio."' and area='tut' and item=3 and ambit=1"); 
		if (!$valoracions) {
			echo 'ERROR: ' . mysql_error(); 
			exit;
		}else{
			while($row=$valoracions->fetch_assoc()){
				$resultItemsTutoria = $mysqli->query("SELECT nom FROM items where curs_escolar=0 and item=3"); 
				if (!$resultItemsTutoria) {
					echo 'ERROR: ' . mysql_error();
					exit;
				}else{
					$i=0;
					while($row=$resultItemsTutoria->fetch_assoc()){	
						$i++;
						$contingutObservacions=$contingutObservacions.'<b>'.$row["nom"].'</b> ';
					}
				}
			}
		}
		
		if($observacionsTutoria!='') $contingutObservacions=$contingutObservacions.'<b>'.$observacionsTutoria.'</b>';
		$observacionsFinals=$observacionsFinals.$contingutObservacions.'</td></tr></table><br/>';
		
		if($contingutObservacions!=''){
			echo $observacionsFinals;
		}



		?>
		<table width="100%" border=0 class="sinborde">
		<tr>
		<td width="60%">Atentament: El tutor/a</td>
		<td width="40%">Pare / Mare / Tutor</td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr>
		<td width="60%">A Sineu, ____ d____________ de 20__ </td>
		<td width="40%">A ________________, ____ d____________ de 20__</td>
		</tr>
		</table>
		</div>
		<br/>
<?php
	}
}
?>
</body>
</html>
