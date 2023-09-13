<?php
   
 //Servidor local
 //$bd = mysqli_connect("localhost","root","","scout");
 
 //Em casa
 $bd = mysqli_connect("localhost","root","usbw","prova");
 
 //Servidor do IFFar
 //$bd = mysqli_connect("localhost","gabriela_ramires","130922","gabriela_ramires");

 date_default_timezone_set('America/Sao_Paulo');

 if ($bd) {
	 mysqli_set_charset($bd, "utf8");
 } else {
	 echo "Não foi possível conectar o BD <br>";
	 echo "Mensagem de erro: ".mysqli_connect_error() ;
	 exit();
 }


?>
