<!DOCTYPE html>
<html>
	<head>
		<title>Disponibilizar Provas</title>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="favicon.ico">
		
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="bootstrap/compiler/bootstrap.css">
        <link rel="stylesheet" href="bootstrap/compiler/style2.css">
	</head>
	<body>

		<?php
			//inclui os arquivos
		    include_once("conectar.php");
		    include_once("funcoes.php");
		    include_once("monta_menu.php");

			function nomeProva($bd, $idProva) {
                $sql = "select descricao from prova where idprova = $idProva";

                //echo $sql;

		    	$lista = mysqli_query($bd, $sql);

		        if ( mysqli_num_rows($lista) == 1 ) {
			          $dados = mysqli_fetch_row($lista);
		              return $dados[0];
		         } else 
		              return "";
		    	}

		    $mensagem = "";

		    if ( isset($_POST["acao"]))
		    	$acao = $_POST["acao"];
		    else 
		    	$acao = "";

		    if ( isset($_POST["idprova"])) {
		    	$idprova = $_POST["idprova"];
		    	$nomeProva = nomeProva($bd,$idprova);
		    } else {
		    	$idprova = "";
		    	$nomeProva = "";
		    }
		    

		    if ( $_SESSION["tipo"] == 'ADM')
				$sql_extra = "";
			else 
				$sql_extra = " and id_usuario = ".$_SESSION["id_usuario"];
		

	        if ($acao == "ativar") {
	        		$sql = " update prova set situacao = 'L' where idprova = $idprova";
				    mysqli_query($bd, $sql);
		    	    $mensagem = "<script> alert('A prova foi liberada') </script>";
		    	    $acao = "";
	        } else {
	        		$botao = "";
	        } 

	        $provaOpcoes = montaSelectBD($bd, "select idprova, descricao from prova where situacao = 'EA'", $idprova , false);

	        mysqli_close($bd);
		?>

		<h2>Liberar Prova</h2>
	
		<?php echo $mensagem; ?>
	
		<form action="provap.php" method="post">
      	<fieldset>
	    <label for="prova" class="campo">Selecionar Prova: </label>

        <?php 
				echo "<select id='idprova' name='idprova' >";
				echo $provaOpcoes; 
				echo "</select><br>";
        ?>
	    
	        
	  </fieldset>
	  <br><br>
	  
	  <?php 

	     if ($acao == "") 
	     	echo " <input type='hidden' name='acao' value='ativar'>
				   <input type='submit' value='Liberar'>";     

	  ?>
      </form>
	</body>
</html>