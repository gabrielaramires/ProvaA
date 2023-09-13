<?php include_once("validar_sessao.php"); ?>
<!DOCTYPE html>

<html>

<head>
	<title>Cadastro de Usuários</title>
	<meta charset="utf-8" />
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

	    if ($_SESSION["tipo"] == "AA" || $_SESSION["tipo"] == "AI") {
	    	echo("<h1>Você não possui permissão para acessar esta página!</h1>");
	    	exit();
	    }
	    	    
	    $mensagem = "";
	    $tabela = "";
	    //definição das variáveis para montar a query
	    $idturma       = "";
	    $nome          = "";
	    $id_usuario    = $_SESSION["id_usuario"];
	    
	    
	    $podeAlterar = "";
	    
	    if ( ! isset($_POST["acao"] ) ) {
	        $descr_acao = "Incluir";
		} else {
			 $acao = strtoupper($_POST["acao"]);
			 
			 if ( $acao == "INCLUIR" || $acao == "SALVAR" ) {

			 	//Manipula as variáveis para evitar problemas com aspas e outros caracteres protegidos do MySQL
				$nome            = mysqli_real_escape_string($bd, $_POST["nome"]);
				$id_usuario      = $_SESSION["id_usuario"];
			 }
			 
			 if ( $acao == "SALVAR" || $acao == "EXCLUIR" || $acao == "BUSCAR") { 
			    //Chave(s) primária(s)
			    $idturma = $_POST["idturma"];
			 }
			 
			 if ( $acao == "INCLUIR") {
				 
			    $sql = "insert into turma 
			             (nome, id_usuario)
			            values (
			             '$nome', $id_usuario)";
			    
			    
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
			    	$idturma = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 $sql = " update turma 
				          set
				            nome = '$nome'
				          where
				            idturma = $idturma";
				            
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
				 
				 $sql = "select * from turma where idturma = $idturma";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					 
					$idturma        = $dados["idturma"];
					$nome              = $dados["nome"];	 
					 
				 }
				 
				 
			 }
		}

		$sql_extra = "";

		if ( $_SESSION["tipo"] != 'ADM')
			 $sql_extra = "where id_usuario = $id_usuario";

		$sql_listar = "select * 
		               from turma 
		               $sql_extra
		               order by nome ";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
					echo "<div class='table-responsive'>";
                    $tabela = "<table class='table table-striped'>";
                    $tabela = $tabela."<thead><tr><th scope='col'>Nome</th><th scope='col'>Alterar</th></tr></thead><tbody>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				$vIdTurma      = $dados["idturma"];
				$vNome             = $dados["nome"];
				
				$alterar = "<form method='post'>
				               <input type='hidden' name='idturma' value='$vIdTurma'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.png' value='submit'>
				            </form>";		            
				
				$tabela = $tabela."<tr><td>$vNome</td><td>$alterar</td></tr>";
				            
			}
			
			$tabela = $tabela."</tbody></table></div>";
			
		} else {
			$tabela = "Não há dados para listar";
		}
		

	    mysqli_close($bd);
	?>

	    <?php echo $mensagem; ?>

	<main role="main">
       <div class="container">
        <form class='card p-2' method='POST' action='turma.php'>

           <h2 class="h3 mb-3 font-weight-normal">Controle e Cadastro de Turmas:</h2>
            <input type="hidden" value="<?php echo $id_usuario ?>" name="id_usuario">
            <div class='form-group'>
                <label for='nome' class='float-left'>Nome</label>
                <input class='form-control' type='text' id='nome' name='nome' value="<?php echo $nome; ?>" <?php echo $podeAlterar; ?>>
            </div>
	       

	      <div class='form-group'>
                <input type='submit' value='Novo' class="btn btn-secondary">
            	<input type="submit" name="acao" value="<?php echo $descr_acao; ?>" class="btn btn-secondary">
            </div>
            
            
         </form>    
       </div> 
   </main>

	    <input type="hidden" name="idturma" value="<?php echo $idturma; ?>">
	
	<br>
	
	   <center><legend>Turmas Cadastradas</legend></center>
	   <main role="main">
   		 <div class="container">
	   <?php
	      echo $tabela;
	   ?>
	  
	   	 </div>
   		</main>
	
</body>

</html>
