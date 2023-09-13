
<!DOCTYPE html>
<html>
	<head>
		<title>Montar Prova</title>
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
                $sql = "SELECT descricao FROM prova WHERE idprova = $idProva";
		    	$lista = mysqli_query($bd, $sql);

		        if ( mysqli_num_rows($lista) == 1 ) {
			          $dados = mysqli_fetch_row($lista);
		              return $dados[0];
		         } else 
		              return "";
		    }

		    $mensagem = "";
		    $tabela = "";

		    $lista_resultados = "";

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
				$sql_extra = " where id_usuario = ".$_SESSION["id_usuario"];
		
            $provaOpcoes = montaSelectBD($bd, "SELECT idprova, descricao FROM prova WHERE situacao = 'F'", $idprova , false);


	        if ($acao == "") 
	            $botao = "listar"; 
	        else {
	        	if ($acao == "listar") {
	        	   $botao = "voltar";

	                        $sql_listar = "select nome, sum(acerto) as acertos
 												from V_RESPOSTAS where idprova = $idprova
													group by nome";
						    $sql_questoes = "select count(*) as num_questoes from questao_prova where  idprova = $idprova";

						    $n_questoes = mysqli_query($bd, $sql_questoes);

						    $dados_questoes = mysqli_fetch_assoc($n_questoes);

						    $vNumQuestoes = $dados_questoes["num_questoes"];

                             //echo $sql_listar;

                             $lista = mysqli_query($bd, $sql_listar);

                             if ( mysqli_num_rows($lista) > 0 ) {

                             	echo "<div class='table-responsive'>";
                   	 			$tabela = "<table class='table table-striped'>";
                    			$tabela = $tabela."<thead style='font-size: 15px'><tr><th scope='col'>Nome</th><th scope='col'>Questões</th><th scope='col'>Acertos</th></tr></thead><tbody>";

		            	        while ( $dados = mysqli_fetch_assoc($lista)) {
				                $vNome       	    = $dados["nome"];
								$vAcertos           = $dados["acertos"];
				                 
                                $tabela = $tabela."<tr><td>$vNome</td><td>$vNumQuestoes</td><td>$vAcertos</td></tr>";
                               }
                               $tabela = $tabela."</tbody></table></div>";
                            }
                        }
	        	}

	        mysqli_close($bd);
		?>
	
		<?php echo $mensagem; ?>
		<main role="main">
    	<div class="container">
		<form class='card p-2' method='post' action='resultados.php'>
			<h2 class="h3 mb-3 font-weight-normal">Resultados</h2>
         <div class='form-group'>
         <label for='categoria' class='float-left'>Selecionar prova:</label>
        <?php 

            if ($nomeProva != "") {
                 echo "<input type='hidden' id='idprova' name='idprova' value='$idprova'>$nomeProva";
            } else {
            	echo "<select class='form-control' id='idprova' name='idprova'>
                     $provaOpcoes          
                     </select>";
            }
        ?>
	    <input type="hidden" name="acao" value="<?php echo $botao; ?>">
        </div>
	  
	  <?php 
	     if ($acao == ""){
	     	echo "<div class='form-group'>
                <input type='submit' value='Ver resultados' class='btn btn-secondary'>
            	</div>";
        }    

	  ?>
      </form>
	  
	  <?php

	  if ($acao == "listar") {
	  		echo "<h2>Resultado da prova $nomeProva</h2>";
	     	echo $tabela;

            echo "<form action='resultados.php' method='post'>";
            echo "<center><div class='form-group'><input type='submit' value='Voltar' class='btn btn-secondary'></div></center";
            echo "</form>";

	     }
	  ?>
	</div>
</main>
	</body>
</html>