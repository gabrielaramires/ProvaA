<?php include_once("validar_sessao.php"); ?>

<!DOCTYPE html>
<html>
<head>
	<title>Página Inicial</title>
	<meta charset="utf-8">
		<link rel="shortcut icon" href="favicon.ico">

		
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="bootstrap/compiler/bootstrap.css">
        <link rel="stylesheet" href="bootstrap/compiler/style2.css">

        <style type="text/css">
          h1{
          position: absolute;
          top: 340px;
          left: 500px;
          }
        </style>

</head>
<body>
	<?php
		//inclui os arquivos
	    include_once("conectar.php");
	    include_once("funcoes.php");
	    include_once("monta_menu.php");
	?>
  <h1>Bem vindo ao ProvA+</h1>
</body>
</html>