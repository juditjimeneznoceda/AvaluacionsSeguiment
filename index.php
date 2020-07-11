<?php
  session_start();
  $usuari=$_SESSION['usuari'];
  if( $_SESSION['usuari']!=null ) {
    echo "<script>location.href='http://iessineu.net/avaluacio/login.php';</script>";
    //header('Location:login.php');
    die();
  }
  ?>
<!DOCTYPE html>
<html>
<head>

  <meta charset="UTF-8"> 
  <title>Avaluacions IES Sineu</title>
  <link rel=stylesheet type="text/css" href="estil.css">
<script src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript">

var any_actual;
var tipus_avaluacio;


var alumnesExp=[];
var idxExp=0;
var editant=false;

function carregaTaulaAlumnes(response,area,nivell){
  var json_obj = JSON.parse(response);


  var nivell=$("#selectNivell option:selected").val();
  var grup=$("#selectGrup option:selected").val();
  var area=$("#selectArea option:selected").val();




  var html="";
  html+="<form name='dades' method='POST' action='alumneBD.php'><table class='tablas' >";
  html+="<th>EXP</th>";
  html+="<th>Nom</th>";
  html+="<th>Ítems</th>";
  html+="<th>Notes i Observacions</th>";
  html+="<th>Envia</th>";

  for (var i in json_obj) {

    alumnesExp[idxExp]=json_obj[i].exp;
    idxExp++;

    html+="<tr>";
    html+="<td>"+json_obj[i].exp+"</td>";
    html+="<td>"+json_obj[i].nom+" "+json_obj[i].llinatge1+"</td>";
    html+="<td><div id='items"+json_obj[i].exp+"'></div></td>";

    html+="<td>";

    if(area!="tut"){
      html+="<input type='number' step='any' size='5' min='0' max='10' disabled placeholder='nota1' id='nota_1_"+json_obj[i].exp+"'  name='nota_1_"+json_obj[i].exp+"' value=''>";
      html+="<input type='number' step='any' size='5' min='0' max='10' disabled placeholder='nota2' id='nota_2_"+json_obj[i].exp+"'  name='nota_2_"+json_obj[i].exp+"' value=''>";
      html+="<input type='number' step='any' size='5' min='0' max='10' disabled placeholder='nota3' id='nota_3_"+json_obj[i].exp+"'  name='nota_3_"+json_obj[i].exp+"' value=''><br>";
    }

    html+="<textarea id='observacio"+json_obj[i].exp+"' maxlength='90' placeholder='Observacions. Màxim 90 caràcters' disabled name='observacio"+json_obj[i].exp+"' ></textarea></td>";

    html+="<td><input id='edita"+json_obj[i].exp+"' type='button' value='edita' onclick='edita("+json_obj[i].exp+",\""+grup+"\",\""+area+"\",\""+nivell+"\")' ></td>";
    html+="</tr>";
  }

  $("#taulaAlumnes").html(html);

  actualitzaDadesBD(nivell,tipus_avaluacio,grup,area);

  
  
}

function actualitzaDadesBD(nivell,tipus_avaluacio,grup,area){
  var params = {
    "nivell" : nivell,
    "tipus_avaluacio" : tipus_avaluacio,
    "grup" : grup,
    "area" : area,
    "any_actual" : any_actual
  };

  $.ajax({
        data:  params,
        url:   'getItems.php',
        dataType: 'html',
        type:  'GET',
        success:  function (items) {
            carregaItems(items);
            $.ajax({
              data:  params,
              url:   'getObservacions.php',
              dataType: 'html',
              type:  'GET',
              success:  function (observacions) {
                  carregaObservacions(observacions);
              }
            });
            $.ajax({
              data:  params,
              url:   'getNotes.php',
              dataType: 'html',
              type:  'GET',
              success:  function (notes) {
                  carregaNotes(notes);
              }
            });
            $.ajax({
              data:  params,
              url:   'getValoracions.php',
              dataType: 'html',
              type:  'GET',
              success:  function (items) {
                  carregaValoracions(items);
              }
            });
        }
      });
}
//$("#selectall").prop("checked", true); 
function carregaValoracions(items){
  var json_obj = JSON.parse(items);
  for (var i in json_obj) {
    $("#item"+json_obj[i].item+"exp"+json_obj[i].alumne).prop("checked", true); 
  }
}

function carregaObservacions(observacions){
  var json_obj = JSON.parse(observacions);
  for (var i in json_obj) {
    $("#observacio"+json_obj[i].alumne).text(json_obj[i].observacio);
  }
}

function carregaNotes(notes){
  var json_obj = JSON.parse(notes);
  for (var i in json_obj) {
    $("#nota_"+json_obj[i].num+"_"+json_obj[i].alumne).val(json_obj[i].nota);
  }
}

function carregaItems(items){
  var json_obj = JSON.parse(items);
  var html='';
  for (var exp in alumnesExp) {
    html="";
    for (var item in json_obj) {
        html+="<input id='item"+json_obj[item].item+"exp"+alumnesExp[exp]+"' class='derecha' type='checkbox' size=5 disabled name='item"+json_obj[item].item+"exp"+alumnesExp[exp]+"' >";
        html+="<label class='izquierda'>"+json_obj[item].nom+" </label>";
    }
    $("#items"+alumnesExp[exp]).html(html);
  }
}

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


function carregaAreas(response){
  var json_obj = JSON.parse(response);
  $("#selectAreas").html("");

  var html='';
  html+='<select class="alineat"  id="selectArea" style="width:200px;" name="grup">';
  html+='<option value="" selected="selected">Area:</option>';

  for (var i in json_obj) {
        html+="<option value=" + json_obj[i].area + ">" + json_obj[i].nom + "</option>";
  }
  html+='</select>';
  $("#selectAreas").html(html);
}
function carregaDadesInici(response){
  var json_obj = JSON.parse(response);
  any_actual=json_obj[0].curs_escolar;
  tipus_avaluacio=json_obj[0].tipus_avaluacio;



  $("#titol").html("Avaluació "+tipus_avaluacio+" Curs escolar: 2019-2020");
}

function carregaNivells(response){

  var json_obj = JSON.parse(response);
  $("#selectNivell").html("");

  var html='';
  html+='<select id="selectNivell" style="width:120px;" name="nivell">';
  html+='<option value="" selected="selected">Nivell:</option>';

  for (var i in json_obj) {
        html+="<option value='" + json_obj[i].nivell + "'>" + json_obj[i].nom_nivell + "</option>";
  }
  html+='</select>';
  $("#selectNivell").html(html);

}

function edita(exp, grup, area, nivell){

  if(editant){
    editant=false;
    $("#edita"+exp).val("edita");
    enableTotsElsBotons(exp);
    updateValoracions(exp,area,nivell);
    actualitzaDadesBD(nivell,tipus_avaluacio,grup,area);

    for (i = 1; i <= 7; i++) {
      $("#item"+i+"exp"+exp).prop( "disabled", true );

    }
    $("#nota_1_"+exp).prop( "disabled", true );
    $("#nota_2_"+exp).prop( "disabled", true);
    $("#nota_3_"+exp).prop( "disabled", true);
    $("#observacio"+exp).prop( "disabled", true );

  } else {
    editant=true;
    $("#edita"+exp).val("Envia");
    disableTotsElsBotons(exp);

    for (i = 1; i <= 7; i++) {
      $("#item"+i+"exp"+exp).prop( "disabled", false );

    }
    $("#nota_1_"+exp).prop( "disabled", false );
    $("#nota_2_"+exp).prop( "disabled", false );
    $("#nota_3_"+exp).prop( "disabled", false );
    $("#observacio"+exp).prop( "disabled", false );
  }


}
function disableTotsElsBotons(exp){
  $(":button").css( "display", "none" );
  $("#edita"+exp).css( "display", "initial" );
  $("#botoFormIni").css( "display", "initial" );
}

function enableTotsElsBotons(exp){
  $(":button").css( "display", "initial" );
}

function updateValoracions(exp,area,nivell){
  var valid = true;


  var nota1 = parseFloat($("#nota_1_"+exp).val());
  var nota2 = parseFloat($("#nota_2_"+exp).val());
  var nota3 = parseFloat($("#nota_3_"+exp).val());
  if(nota1<0 || nota1>10){
    alert("La nota 1 ha d'estar entre 1 i 10");
    nota1=0;
  }
  if(nota2<0 || nota2>10){
    alert("La nota 2 ha d'estar entre 1 i 10");
    nota2=0;
  }
  if(nota3<0 || nota3>10){
    alert("La nota 3 ha d'estar entre 1 i 10");
    nota3=0;
  }

  var item1 = $("#item1exp"+exp).prop("checked");
  var item2 = $("#item2exp"+exp).prop("checked");
  var item3 = $("#item3exp"+exp).prop("checked");
  var item4 = $("#item4exp"+exp).prop("checked");
  var item5 = $("#item5exp"+exp).prop("checked");
  var item6 = $("#item6exp"+exp).prop("checked");
  var item7 = $("#item7exp"+exp).prop("checked");
  
  var observacio = $("#observacio"+exp).val();

  var params = {
        "item1" : item1,
        "item2" : item2,
        "item3" : item3,
        "item4" : item4,
        "item5" : item5,
        "item6" : item6,
        "item7" : item7,
        "nota1" : nota1,
        "nota2" : nota2,
        "nota3" : nota3,
        "observacio" : observacio,
        "exp" : exp,
        "area" : area,
        "nivell" : nivell,
        "tipus_avaluacio" : tipus_avaluacio,
        "any_actual" : any_actual
  };
  if(valid){
    $.ajax({
        data:  params,
        url:   'updateValoracions.php',
        dataType: 'html',
        type:  'GET',
        success:  function (response) {
            alert("La informació s'ha desat correctament.");
        }
    });
  }

}


$(document).ready(function(){
  $.ajax({
        url:   'getDadesInici.php',
        dataType: 'html',
        type:  'GET',
        success:  function (response) {
            carregaDadesInici(response);
        }
    });

  $.ajax({
    url:   'getNivells.php',
    dataType: 'html',
    type:  'GET',
    success:  function (response) {
      carregaNivells(response);
      $("#selectNivell").change(function(){
        $(".amaga").css("display", "initial ");

        var nivellSel=$("#selectNivell option:selected").val();
        
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
        $.ajax({
            data:  params,
            url:   'getAreas.php',
            dataType: 'html',
            type:  'GET',
            success:  function (response) {
                carregaAreas(response);
            }
        });
        $("#botoFormIni").click(function(){
          var nivell=$("#selectNivell option:selected").val();
          var grup=$("#selectGrup option:selected").val();
          var area=$("#selectArea option:selected").val();
          var params = {
            "nivell" : nivell,
            "grup" : grup,
            "area" : area,
            "any_actual" : any_actual
          };
          $.ajax({
            data:  params,
            url:   'getAlumnes.php',
            dataType: 'html',
            type:  'GET',
            success:  function (response) {
                carregaTaulaAlumnes(response,area,nivell);
            }
          });
        });
      });
    }
  });
});
</script>


</head>
<body>
  <h1 id="titol"></h1>
  <h3>Selecciona un nivell, grup i àrea: </h3>
  <table class="tablas">
    <tr>
      <td><span class="nombres">1</span></td>
      <td>
          <form class="formulari" name=form_nivell method="POST" action="index.php">
            <div id="selectNivell"></div>
          </form>
        </td>
        <td id="pasa2"></td>
        <td id="grups">
          <form id="formini" class="formulari" name="form_grup" >
            <div id="selectGrups"></div>
            <div id="selectAreas"></div>
            <input  id="botoFormIni" class="amaga alineat" type="button" value="carrega">
          </form>

          
        </td>
      </tr>
    </table>
    <br>
    <table class="tablas">

      <div id="taulaAlumnes"></div>
    </table>

</body>
</html>
