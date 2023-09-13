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
</head>
<body>
	<?php
		//inclui os arquivos
	    include_once("conectar.php");
	    include_once("funcoes.php");
	    include_once("monta_menu.php");

	    $mensagem = "";
	    $tabela = "";
	    
	    $idquestao        = "";
	    $descricao        = "";
	    $data			  = "";
	    $hora			  = "";
	    $situacao         = "";
	    
	    $id_usuario = $_SESSION["id_usuario"];
		
		$sql_listar = "select p.* from prova p, aluno_prova ap 
		               where ap.idprova = p.idprova and ap.id_usuario = $id_usuario ";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
			echo "<div class='table-responsive'>";
                    $tabela = "<table class='table table-striped'>";
			$tabela = $tabela."<thead style='font-size: 15px'><tr><th scope='col'>Avaliação</th><th scope='col'>Data</th><th scope='col'>Horário</th><th scope='col'>Ação</th></tr></thead><tbody>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
					$vIdprova          = $dados["idprova"];
					$vDescricao        = $dados["descricao"];
					$vData             = $dados["data"];
					$vHora             = $dados["hora"];
					$vSituacao         = $dados["situacao"];

					$dataauxiliar = date('d/m/Y', strtotime($vData));

					if ($vSituacao == "L") {
						$realizar = "<form method='post' action='prova.php'>
				              		 <input type='hidden' name='idprova' value='$vIdprova'>
				              		 <input type='hidden' name='id_usuario' value='$id_usuario'>
				               		 <input type='hidden' name='acao' value='REALIZAR'>
				              		 <input type='submit' name='realizar' value='Realizar Prova' class='btn btn-secondary'>
				            </form>";	
					} 
					if ($vSituacao == "EA")
							$realizar = "";
					
					if ($vSituacao == "F") {
						$realizar = "<form method='post' action='prova.php'>
				              		 <input type='hidden' name='idprova' value='$vIdprova'>
				              		 <input type='hidden' name='id_usuario' value='$id_usuario'>
				               		 <input type='hidden' name='acao' value='CONSULTAR'>
				              		 <input type='submit' name='consultar' value='Consultar Prova' class='btn btn-secondary'>
				            </form>";	
						}

					$tabela = $tabela."<tr><td>$vDescricao</td><td>$dataauxiliar</td><td>$vHora</td><td>$realizar</td></td>";
					 	
			}
				$tabela = $tabela."</tbody></table></div>";
		} else {
			$tabela = "Não há dados para listar";
		}
	    mysqli_close($bd);
		?>


		 <fieldset>
		   <legend><h2>Provas</h2></legend>
		   
		   <?php
		      echo $tabela;
		   ?>
		   
		        
		 </fieldset>	
</body>
</html>