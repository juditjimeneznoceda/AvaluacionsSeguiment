
<?php
 ob_start();
  session_start();
  $usuari=$_SESSION['usuari'];
  echo $usuari;
  if( $_SESSION['usuari']!=null ) {
    //echo "<script>location.href='http://iessineu.net/avaluacio/login.php';</script>";
    header('Location:login.php');
    die();
  }
  include 'connect.php';
  
  if ($myquery = $mysqli->query("SELECT curs_escolar, tipus_avaluacio FROM curs_escolar order by curs_escolar desc")) {
    $row = $myquery->fetch_assoc();
    $any_actual=$row["curs_escolar"];
    $tipus_avaluacio=$row["tipus_avaluacio"];
    $myquery->close();
  }

  $_SESSION['any_actual']= $any_actual;
  $_SESSION['tipus_avaluacio']=$tipus_avaluacio;


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
  	<title>Avaluació <?php echo $tipus_avaluacio.". ".$any_actual."-".($any_actual+1);  ?></title>
  	<link rel=stylesheet type="text/css" href="estil.css">
	<script src="js/jquery-3.4.1.min.js"></script>
	<script type="text/javascript">
		  <?php 
		  echo 'var any_actual='.$any_actual.';';
		  echo 'var tipus_avaluacio="'.$tipus_avaluacio.'";';
		  ?>

		function carregaGrups(response){
		  var json_obj = JSON.parse(response);
		  $("#pasa2").html('<span class="nombres">2</span>');

		  var html='';
		  html+='<select class="alineat" id="selectGrup" style="width:60px;" name="grup">';
		  html+='<option value="" selected="selected">Grup:</option>';

		  for (var i in json_obj) {
		        html+="<option value=" + json_obj[i] + ">" + json_obj[i] + "</option>";
		  }
		  html+='</select>';
		  $("#selectGrups").html(html);
		}

		function carregaTaulaAlumnes(response,nivell){
		  var json_obj = JSON.parse(response);

		  var nivell=$("#selectNivell").val();
		  var grup=$("#selectGrup").val();


		  var html="";
		  html+="<table class='tablas' >";
		  html+="<tr><th colspan=3>ALUMNES </th></tr>";
		  html+="<tr><th colspan=3><a href='reporte.php?exp=0&any_actual="+any_actual+"&tipus_avaluacio="+tipus_avaluacio+"&nivell="+nivell+"&grup="+grup+"&num_alumnes=tots' target='new' class='boto' title='pitjar aqui per generar pdf'>Generar pdf amb les notes de tots els alumnes del curs</a></th></tr>";
		  html+="<th>EXP</th>";
		  html+="<th>Nom</th>";
		  html+="<th>Bulletí</th>";

		  for (var i in json_obj) {
		    html+="<tr>";
		    html+="<td>"+json_obj[i].exp+"</td>";
		    html+="<td>"+json_obj[i].nom+" "+json_obj[i].llinatge1+"</td>";
		    html+="<td><a href='reporte.php?exp="+json_obj[i].exp+"&any_actual="+any_actual+"&tipus_avaluacio="+tipus_avaluacio+"&nivell="+nivell+"&grup="+grup+"&num_alumnes=1' target='new' class='boto' title='pitjar aqui per generar pdf'><img src='images/pdf_icon.png' alt='imprimir notes'/></a></td>";
		    html+="</tr>";
		  }
		  html+="</table>";

		  $("#taulaAlumnes").html(html);
		}


		$(document).ready(function(){
		  $("#selectNivell").change(function(){
		    $(".amaga").css("display", "initial ");
		    var nivellSel=$(this).val();

		    var params = {
		        "nivell" : nivellSel,
		        "any_actual" : any_actual
		    };
		    $.ajax({
		        data:  params,
		        url:   'getGrups.php',
		        dataType: 'html',
		        type:  'GET',
		        success:  function (response) {
		            carregaGrups(response);
		        }
		    });
		    

		    $("#botoFormIni").click(function(){
		      var nivell=$("#selectNivell").val();
		      var grup=$("#selectGrup").val();
		      var params = {
		        "nivell" : nivell,
		        "grup" : grup,
		        "any_actual" : any_actual
		      };
		      

		      $.ajax({
		        data:  params,
		        url:   'getAlumnes.php',
		        dataType: 'html',
		        type:  'GET',
		        success:  function (response) {
		            carregaTaulaAlumnes(response,nivell);
		        }
		      });


		    });

		  });
		});

	</script>


</head>
<body>
  <h1>Avaluació <?php echo $tipus_avaluacio.". Curs escolar: ".$any_actual."-".($any_actual+1);  ?></h1>
  <h3>Selecciona un nivell i grup: </h3>
  <table class="tablas">
    <tr>
      <td><span class="nombres">1</span></td>
      <td>
          <form class="formulari" name=form_nivell method="POST" action="index.php">
            <select id="selectNivell" name="nivell" style="width:120px;">
              <option value="0">Nivell:</option>

              <?php
              if ($myquery = $mysqli->query("SELECT * FROM nivells")) {
                while($row = $myquery->fetch_assoc()){
                  echo '<option value="'.$row["nivell"].'" >'.$row["nom_nivell"].'</option>';
                }
              }
              ?>
            </select>
            
          </form>
        </td>
        <td id="pasa2"></td>
        <td id="grups">
          <form id="formini2" class="formulari" name="form_grup" >
            <div id="selectGrups"></div>
            <input id="botoFormIni" class="amaga alineat" type="button" value="carrega">
          </form>

          
        </td>
      </tr>
    </table>
    <br>
   
    <div id="taulaAlumnes"></div>

</body>
</html>

<?php

ob_end_flush();

?>