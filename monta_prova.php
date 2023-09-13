
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

		    $lista_questoes = "";

		    $prova_gerada = "";

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
		
            $provaOpcoes = montaSelectBD($bd, "SELECT idprova, descricao FROM prova WHERE situacao = 'EA'", $idprova , false);


	        if ($acao == "") 
	            $botao = "listar"; 
	        else {
	        	if ($acao == "listar") {
	        	   $botao = "cadastrar";

	        	   $sql_listar = "select questao_prova.idquestprov, questao.idquestao, questao.descricao 
                                  from   questao
                                            left join questao_prova on (questao.idquestao = questao_prova.idquestao AND questao_prova.idprova = $idprova)
                                  $sql_extra ";


                    $questoes = mysqli_query($bd, $sql_listar);

                   
		
		            if ( mysqli_num_rows($questoes) > 0 ) {

		            	while ( $dados = mysqli_fetch_assoc($questoes)) {
				                $vIdquestprov       = $dados["idquestprov"];
				                $vIdquestao         = $dados["idquestao"];
				                $vDescricao         = $dados["descricao"];

				                if ($vIdquestprov > 0)
				                	$marcado = 'checked';
				                else 
				                	$marcado = '';

				                $lista_questoes = $lista_questoes."<div class='form-group form-check'>
    							<input type='checkbox' class='form-check-input' name='idquestao[]' value='$vIdquestao' $marcado><label class='form-check-label'>$vDescricao</label>
 									 </div>";
				        }
		            }
	        	} else {
	        		if ($acao == "cadastrar") {
                        $botao = "";

                        if ( isset($_POST["idquestao"])) {
		    	             $idquestao = $_POST["idquestao"];

		    	             if (count($idquestao) > 0) {

		    	             	$sql = " delete from questao_prova where idprova = $idprova";
				                mysqli_query($bd, $sql);

		    	             	for ($k=0;$k<count($idquestao);$k++) {

		    	             		$idq =  $idquestao[$k];

		    	             		$sql_aux = "select * from questao_prova where idquestao = $idq and idprova = $idprova";

		    	             		//echo $sql_aux;

		    	             		$questoesProva = mysqli_query($bd, $sql_aux);

		    	             		if ( mysqli_num_rows($questoesProva) == 0 ) {
                                          $sql = "insert into questao_prova (idprova, idquestao) values ($idprova, $idq)";
				                          mysqli_query($bd, $sql);
				                    }

		    	             	}
		    	             }


                             $sql_listar = "select questao_prova.idquestprov, questao.*
                                  from      questao, questao_prova 
                                  where     questao.idquestao = questao_prova.idquestao and questao_prova.idprova = $idprova";

                             //echo $sql_listar;

                             $questoes = mysqli_query($bd, $sql_listar);

                             if ( mysqli_num_rows($questoes) > 0 ) {

		            	        while ( $dados = mysqli_fetch_assoc($questoes)) {
				                   $vIdquestprov       = $dados["idquestprov"];
				                   $vIdquestao         = $dados["idquestao"];
				                   $vDescricao         = $dados["descricao"];
				                   $vAlt1              = $dados["alt1"];
				                   $vAlt2              = $dados["alt2"];
				                   $vAlt3              = $dados["alt3"];
				                   $vAlt4              = $dados["alt4"];
				                   $vAlt5              = $dados["alt5"];
				                 
                                   $prova_gerada = $prova_gerada."<h6>$vDescricao</h6>";
                                   $prova_gerada = $prova_gerada."<input type='radio'>$vAlt1<br><input type='radio'>$vAlt2<br><input type='radio'>$vAlt3<br><input type='radio'>$vAlt4<br><input type='radio'>$vAlt5<br><br>";
                               }
                            }


		    	        } else {
		    	        	$sql = "delete from questao_prova where idprova = $idprova";
				            mysqli_query($bd, $sql);
		    	        }
	        		} else {
	        		   $botao = "";
	        		}
	        	}
	        } 

	        mysqli_close($bd);
		?>
	
		<?php echo $mensagem; ?>
		<main role="main">
    	<div class="container">
		<form class='card p-2' method='post' action='monta_prova.php'>
			<h2 class="h3 mb-3 font-weight-normal">Montar prova</h2>
         <div class='form-group'>
         <label for='categoria' class='float-left'>Selecionar prova:</label>
        <?php 

            if ($nomeProva != "") {
                 echo "&nbsp<input type='hidden' id='idprova' name='idprova' value='$idprova'>$nomeProva";
            } else {
            	echo "<select class='form-control' id='idprova' name='idprova'>
                     $provaOpcoes          
                     </select>";
            }
        ?>
	    <input type="hidden" name="acao" value="<?php echo $botao; ?>">
        </div>
	  
	  <?php 

	     echo $lista_questoes;

	     if ($acao == ""){
	     	echo "<div class='form-group'>
                <input type='submit' value='Escolher questoes' class='btn btn-secondary'>
            	</div>";
        }
	     else if ($acao == "listar") {
	     	echo "<div class='form-group'>
                <input type='submit' value='Salvar questoes' class='btn btn-secondary'>
            	</div>";
	     }      

	  ?>
      </form>
	  
	  <?php

	  if ($acao == "cadastrar") {
	  		echo "<h2>Pré-visualização da prova: </h2>";
	     	echo $prova_gerada;

            echo "<form action='monta_prova.php' method='post'>";
            echo "<input type='hidden' id='idprova' name='idprova' value='$idprova'>";
            echo "<input type='hidden' id='acao' name='acao' value='listar'>";
            echo "<br><input type='submit' value='Alterar' class='btn btn-secondary'>";
            echo "</form>";


            echo "<form action='monta_prova.php' method='post'>";
            echo "<br><input type='submit' value='Salvar' class='btn btn-secondary'>";
            echo "</form>";



	     }
	  ?>
	</div>
</main>
	</body>
</html>