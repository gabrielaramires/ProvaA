<?php include_once("validar_sessao.php"); ?>
<!DOCTYPE html>

<html>

<head>
	<title>Montagem de Provas</title>
	<meta charset="utf-8" />
	<link rel="shortcut icon" href="favicon.ico">

	
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" href="bootstrap/compiler/bootstrap.css">
        <link rel="stylesheet" href="bootstrap/compiler/style2.css">
	
</head>

<body>
	
	<?php 
	    include_once("conectar.php");
	    include_once("funcoes.php");
	    include_once("monta_menu.php"); 
	    
	    $mensagem = "";
	    $tabela = "";
	    
	    $idprova        = "";
	    $descricao      = "";
	    $situacao		= "";
	    $data    	    = "";
	    $hora           = "";  
	    
	    $podeAlterar = "";
	    
	    if ( ! isset($_POST["acao"] ) ) {
	        $descr_acao = "Incluir";
		} else {
			 $acao = strtoupper($_POST["acao"]);
			 
			 if ( $acao == "INCLUIR" || $acao == "SALVAR" ) {

				$idprova           = mysqli_real_escape_string($bd, $_POST["idprova"]);
				$descricao 		   = mysqli_real_escape_string($bd, $_POST["descricao"]);
				$situacao          = mysqli_real_escape_string($bd, $_POST["situacao"]);
				$data              = mysqli_real_escape_string($bd, $_POST["data"]);
				$hora              = mysqli_real_escape_string($bd, $_POST["hora"]);
			 }
			 
			 if ( $acao == "SALVAR" || $acao == "EXCLUIR" || $acao == "BUSCAR" || $acao == "ATIVAR" || $acao == "FINALIZAR") { 
			    //Chave(s) primária(s)
			    $idprova = $_POST["idprova"];
			 }
			 
			 if ( $acao == "INCLUIR") {

			 	$id_usuario = $_SESSION["id_usuario"];
				 
			    $sql = "insert into prova 
			             (descricao, situacao, data, hora, id_usuario)
			            values (
			             '$descricao', 
			             '$situacao',  
			             '$data',
			          	 '$hora',
			          		$id_usuario)";
			    
			    
			    if ( ! mysqli_query($bd, $sql) ) {
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Não foi possível fazer a inclusão (dados duplicados)</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao 
						inserir os dados: </h3> 
						<h3>".mysqli_error($bd)."</h3> 
						<h4>".mysqli_errno($bd)."</h4>";
				    }

					$descr_acao = "Incluir";
				
				} else {
			    	$descr_acao = "Salvar";
			    	//Chave(s) primária(s)
			    	$idprova = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 $sql = " update prova 
				          set
				            descricao = '$descricao',
				            situacao = '$situacao',
				            data = '$data',
				            hora = '$hora'
				            where
				            idprova = $idprova";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Não foi possível fazer a inclusão (dados duplicados)</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao 
						alterar os dados: </h3> 
						<h3>".mysqli_error($bd)."</h3>".$sql. 						
						"<h4>".mysqli_errno($bd)."</h4>";
				    }
				}         
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 $sql = "select * from prova where idprova = $idprova";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					 
					$idprova          = $dados["idprova"];
					$descricao        = $dados["descricao"];
					$situacao         = $dados["situacao"];
					$data             = $dados["data"];
					$hora             = $dados["hora"];
					 
				 }	 
			 }
				if (strtoupper($acao) == "ATIVAR") {
					$descr_acao = "Salvar";
	        		$sql = "update prova set situacao = 'L' where idprova = $idprova";

				    $resultado = mysqli_query($bd, $sql);

		    	}

		    	if (strtoupper($acao) == "FINALIZAR") {
					$descr_acao = "Salvar";
	        		$sql = "update prova set situacao = 'F' where idprova = $idprova";

				    $resultado = mysqli_query($bd, $sql);

		    	}
		}
		
		if ( $_SESSION["tipo"] == 'ADM')
			$sql_extra = "";
		else 
			$sql_extra = " where id_usuario = ".$_SESSION["id_usuario"];

		$sql_listar = "select * 
		               from prova $sql_extra";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
			echo "<div class='table-responsive'>";
            $tabela = "<table class='table table-striped'>";
            $tabela = $tabela."<thead><tr><th scope='col'>Descrição</th><th scope='col'>Situação</th><th scope='col'>Data</th><th scope='col'>Hora</th><th scope='col'>Alterar</th><th scope='col'>Opções</th></tr></thead><tbody>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
					$vIdProva        = $dados["idprova"];
					$vDescricao      = $dados["descricao"];
					$vSituacao       = $dados["situacao"];
					$vData           = $dados["data"];
					$vHora           = $dados["hora"];
					$dataauxiliar    = date('d/m/Y', strtotime($vData));

				$alterar = "";
				$liberar = "";

				if ($vSituacao == "EA") {

				$alterar = "<form method='post'>
				               <input type='hidden' name='idprova' value='$vIdProva'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.png' value='submit'>
				            </form>";

				$liberar = "<form method='post'>
							   <input type='hidden' name='idprova' value='$vIdProva'>
				               <input type='hidden' name='acao' value='ATIVAR'>
				               <input type='submit' value='Liberar' class='btn btn-secondary'>
				            </form>";
				}

				if ($vSituacao == "L") {
					$liberar = "<form method='post'>
							   <input type='hidden' name='idprova' value='$vIdProva'>
				               <input type='hidden' name='acao' value='FINALIZAR'>
				               <input type='submit' value='Finalizar' class='btn btn-secondary'>
				            </form>";
				}

				$tabela = $tabela."<tr><td>$vDescricao</td><td>$vSituacao</td><td>$dataauxiliar</td><td>$vHora</td><td>$alterar</td><td>$liberar</td></td>";            
			}
			
			$tabela = $tabela."</tbody></table></div>";			
		} else {
			$tabela = "<div class='alert alert-danger' role='alert'> Não há provas para listar</div>";
		}

		
		$situacaoVal    = array("EA","L","F");
		$situacaoDescr  = array("Em Andamento","Liberada", "Finalizada");
		$situacaoOpcoes = montaSelect($situacaoVal, $situacaoDescr, $situacao, false); 
		

	    mysqli_close($bd);
	?>
	
	<?php echo $mensagem; ?>

	<main role="main">
       <div class="container">
        <form class='card p-2' method='POST' action='cria_prova.php'>

           <h2 class="h3 mb-3 font-weight-normal">Controle e Cadastro de Provas:</h2>
            <input type="hidden" value="<?php echo $id_usuario ?>" name="id_usuario">
            <div class='form-group'>
                <label for='descricao' class='float-left'>Descrição</label>
                <input class='form-control' type='text' id='descricao' name='descricao' value="<?php echo $descricao; ?>" <?php echo $podeAlterar; ?>>
            </div>

            <div class='form-group'>
            	<label for="situacao" class='float-left mr-2'>Situação: </label>
				    <select id="situacao" name="situacao" >
				      <?php echo $situacaoOpcoes; ?>
				    </select><br>
            </div>

            <div class='form-group'>
            	<label for="data" class="campo">Data: </label>
	    		<input type="date" id="data" name="data" size="60" value="<?php echo $data; ?>"> <br>
            </div>
	       
	       <div>
			    <label for="hora" class="campo">Hora: </label>
			    <input type="time" id="hora" name="hora" size="60" value="<?php echo $hora; ?>"> <br>
	       </div>

	       	<input type="hidden" name="idprova" value="<?php echo $idprova; ?>">

	     	 <div class='form-group'>
                <input type='submit' value='Novo' class="btn btn-secondary">
            	<input type="submit" name="acao" value="<?php echo $descr_acao; ?>" class="btn btn-secondary">
           	 </div>
            
            
         </form>    
       </div> 
   </main>
	
	<br>
	
	   <center><legend>Provas Cadastradas</legend></center>
	   <main role="main">
   		 <div class="container">
	   <?php
	      echo $tabela;
	   ?>
	   
	    </div>
	</main>
		
</body>

</html>
