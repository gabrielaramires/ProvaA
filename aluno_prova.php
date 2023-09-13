<!DOCTYPE html>
<html>
	<head>
		<title>Disponibilizar Provas</title>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="favicon.ico">

		
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="bootstrap/compiler/bootstrap.css">
        <link rel="stylesheet" href="bootstrap/compiler/style2.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

	</head>
	<body>

		<?php
			//inclui os arquivos
		    include_once("conectar.php");
		    include_once("funcoes.php");
		    include_once("monta_menu.php");

			function nomeProva($bd, $idProva) {
                $sql = "select descricao from prova where idprova = $idProva";
		    	$lista = mysqli_query($bd, $sql);

		        if ( mysqli_num_rows($lista) == 1 ) {
			          $dados = mysqli_fetch_row($lista);
		              return $dados[0];
		         } else 
		              return "";
		    }

		    $mensagem = "";

		    $lista_alunos = "";

		    $alunos_selecionados = "";

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
		
            $provaOpcoes = montaSelectBD($bd, "select idprova, descricao from prova where situacao = 'EA'", $idprova , false);

	        if ($acao == "") 
	            $botao = "listar"; 
	        else {
	        	if ($acao == "listar") {
	        	   $botao = "cadastrar";

	        	   $sql_listar = "select aluno_prova.idalunoprov, usuario.id_usuario, usuario.nome 
                                  from   usuario
                                            left join aluno_prova on (usuario.id_usuario = aluno_prova.id_usuario and aluno_prova.idprova = $idprova) where tipo = 'AA'
                                  ";


                    $alunos = mysqli_query($bd, $sql_listar);
		
		            if ( mysqli_num_rows($alunos) > 0 ) {

		            	while ( $dados = mysqli_fetch_assoc($alunos)) {
				                $vIdalunoprov       = $dados["idalunoprov"];
				                $vId_Usuario        = $dados["id_usuario"];
				                $vNome              = $dados["nome"];

				                if ($vIdalunoprov > 0)
				                	$marcado = 'checked';
				                else 
				                	$marcado = '';

				                $lista_alunos = $lista_alunos."<div class='form-check'><input class='form-check-input' type='checkbox' name='id_usuario[]' value='$vId_Usuario' $marcado><label class='form-check-label' for='defaultCheck1'>$vNome</label></div>";
                                
                                
				        }
		            }
	        	} else {
	        		if ($acao == "cadastrar") {
                        $botao = "";

                        if ( isset($_POST["id_usuario"])) {
		    	             $id_usuario = $_POST["id_usuario"];

		    	             if (count($id_usuario) > 0) {

		    	             	$sql = " delete from aluno_prova where idprova = $idprova";
				                mysqli_query($bd, $sql);

		    	             	for ($k=0;$k<count($id_usuario);$k++) {

		    	             		$idu =  $id_usuario[$k];

                                    $sql = "insert into aluno_prova (idprova, id_usuario) values ($idprova, $idu)";
				                    mysqli_query($bd, $sql);

		    	             	}
		    	             }


                             $sql_listar = "select aluno_prova.idalunoprov, usuario.*
                                  from      usuario, aluno_prova 
                                  where     usuario.id_usuario = aluno_prova.id_usuario and aluno_prova.idprova = $idprova";

                             //echo $sql_listar;

                             $alunos = mysqli_query($bd, $sql_listar);

                             if ( mysqli_num_rows($alunos) > 0 ) {

                             	echo "<div class='table-responsive'>";
				                    $alunos_selecionados = "<table class='table table-striped'>";
				                    $alunos_selecionados = $alunos_selecionados."<thead><tr><th scope='col'>Nome</th></tr></thead><tbody>";


		            	        while ( $dados = mysqli_fetch_assoc($alunos)) {
				                   $vIdalunoprov        = $dados["idalunoprov"];
				                   $vId_Usuario         = $dados["id_usuario"];
				                   $vNome               = $dados["nome"];
				                 
                                   $alunos_selecionados = $alunos_selecionados."<tr><td>$vNome</tr></td>";
                               }
                               $alunos_selecionados = $alunos_selecionados."</tbody></table>";
                            }


		    	        } else {
		    	        	$sql = " delete from aluno_prova where idprova = $idprova";
				            mysqli_query($bd, $sql);
		    	        }
	        		} else 
	        		       $botao = "";
	      
	        	}
	        } 

	        mysqli_close($bd);
		?>

		<?php echo $mensagem; ?>
		<main role="main">
    	<div class="container">
		<form class='card p-2' method='post' action='aluno_prova.php'>
			<h2 class="h3 mb-3 font-weight-normal">Relação aluno e prova</h2>
         <div class='form-group'>
         <label for='categoria' class='float-left'>Selecionar prova:</label>

        <?php 

            if ($nomeProva != "") {
                 echo "<input type='hidden' id='idprova' name='idprova' value='$idprova'> $nomeProva";
            } else {
            	echo "<select class='form-control' id='idprova' name='idprova'>
                     $provaOpcoes          
                     </select>";
            }
        ?>
	    <input type="hidden" name="acao" value="<?php echo $botao; ?>">
	    </div>
		    <?php 
		     if ($acao == "") 
		     	echo "<div class='form-group'>
                <input type='submit' value='Escolher alunos' class='btn btn-secondary'>
            	</div>";
		     else if ($acao == "listar") {
		     	echo "<h2>Alunos:</h2>";
		     	echo "<div>$lista_alunos</div>";
		     	echo "<div class='form-group'>
                <input type='submit' value='Gravar' class='btn btn-secondary'>
            	</div>";

            	//echo "<script>
				//		$(document).ready(function(){
				//		  $('#myInput').on('keyup', function() {
				//		    var value = $(this).val().toLowerCase();
				//		    $('#myDIV *').filter(function() {
				//		      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				//		    });
				//		  });
				//		});
				//		</script>";
		     }      

		  ?>
        
		<?php

	  if ($acao == "cadastrar") {
	  		echo "<p>Lista de alunos:</p>";
	     	echo $alunos_selecionados;

            echo "<input type='hidden' id='idprova' name='idprova' value='$idprova'>";
            echo "<input type='hidden' id='acao' name='acao' value='listar'>";
            echo "<div class='form-group'>
                <input type='submit' value='Alterar' class='btn btn-secondary'>
           		<a class='btn btn-secondary' href='aluno_prova.php'' role='button'>Salvar</a>
            	</div>";


	     }
	  ?>	  
	  	</form>
			</div>
		</main>
	</body>
</html>