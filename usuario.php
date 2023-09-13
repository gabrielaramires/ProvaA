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
	    $id_usuario       = "";
	    $nome             = "";
	    $cpf			  = "";
	    $senha            = "";
	    $tipo             = "";
	    $idturma		  = "";
	    
	    
	    $podeAlterar = "";
	    
	    if ( ! isset($_POST["acao"] ) ) {
	        $descr_acao = "Incluir";
		} else {
			 $acao = strtoupper($_POST["acao"]);
			 
			 if ( $acao == "INCLUIR" || $acao == "SALVAR" ) {

			 	//Manipula as variáveis para evitar problemas com aspas e outros caracteres protegidos do MySQL
				$nome            = mysqli_real_escape_string($bd, $_POST["nome"]);
				$cpf 			 = mysqli_real_escape_string($bd, $_POST["cpf"]);
				$senha           = mysqli_real_escape_string($bd, $_POST["senha"]);
				$tipo            = mysqli_real_escape_string($bd, $_POST["tipo"]);
				$idturma         = mysqli_real_escape_string($bd, $_POST["idturma"]);
			 }
			 
			 if ( $acao == "SALVAR" || $acao == "EXCLUIR" || $acao == "BUSCAR") { 
			    //Chave(s) primária(s)
			    $id_usuario = $_POST["id_usuario"];
			 }
			 
			 if ( $acao == "INCLUIR") {
				 
			    $sql = "insert into usuario 
			             (nome, cpf, senha, tipo, idturma)
			            values (
			             '$nome', 
			             '$cpf',  
			             '$senha',
			             '$tipo',
			             '$idturma')";
			    
			    
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
			    	$id_usuario = mysqli_insert_id($bd);
				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 $sql = " update usuario 
				          set
				            nome = '$nome',
				            cpf = '$cpf',
				            senha ='$senha',
				            tipo = '$tipo',
				            idturma = '$idturma'
				          where
				            id_usuario = $id_usuario";
				            
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
				 
				 $sql = "select * from usuario where id_usuario = $id_usuario ";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					 
					$id_usuario        = $dados["id_usuario"];
					$nome              = $dados["nome"];
					$cpf               = $dados["cpf"];
					$senha             = $dados["senha"];
					$tipo              = $dados["tipo"];
					$idturma           = $dados["idturma"]; 

					 
				 }
			 }
		}

		if ( $_SESSION["tipo"] == 'ADM')
			$sql_listar = "select usuario.*, turma.nome as turma from usuario, turma where usuario.idturma = turma.idturma order by usuario.nome";
		else
			$sql_listar = "select usuario.*, turma.nome as turma from usuario, turma 
		                   where usuario.idturma = turma.idturma and usuario.tipo = 'AA' and
		                         turma.id_usuario = ".$_SESSION["id_usuario"]." order by usuario.nome";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {

					echo "<div class='table-responsive'>";
                    $tabela = "<table class='table table-striped'>";
                    $tabela = $tabela."<thead><tr><th scope='col'>Nome</th><th scope='col'>CPF</th><th scope='col'>Tipo</th><th scope='col'>Turma</th><th scope='col'>Alterar</th></tr></thead><tbody>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
				$vId_Usuario       = $dados["id_usuario"];
				$vNome             = $dados["nome"];
				$vCpf              = $dados["cpf"];
				$vTipo             = $dados["tipo"];
				$vIdTurma          = $dados["idturma"];
				$vTurma            = $dados["turma"];
				
				$alterar = "<form method='post'>
				               <input type='hidden' name='id_usuario' value='$vId_Usuario'>
				               <input type='hidden' name='acao' value='BUSCAR'>
				               <input type='image' src='./img/alterar.png' value='submit'>
				            </form>";
				            
				$tabela = $tabela."<tr><td>$vNome</td><td>$vCpf</td><td>$vTipo</td><td>$vTurma</td><td>$alterar</td></tr>";
				            
			}
			
			$tabela = $tabela."</tbody></table></div>";
			
		} else {
			$tabela = "<div class='alert alert-danger' role='alert'> Não há usuários para listar</div>";		}
		
		if ($_SESSION["tipo"] == "ADM") {
			$tipoVal    = array("ADM","PA","PI");
			$tipoDescr  = array("Administrador","Professor Ativo","Professor Inativo");
		} else {
			if ($_SESSION["tipo"] == "PA") {
				$tipoVal    = array("AA","AI");
				$tipoDescr  = array("Aluno Ativo","Aluno Inativo");
			}
		}

		$tipoOpcoes = montaSelect($tipoVal, $tipoDescr, $tipo, false);

		$restricao = "";
		$id_usr = $_SESSION["id_usuario"];

		if ($_SESSION["tipo"] != "ADM") 
             $restricao = " where id_usuario = $id_usr";

		$turmaOpcoes = montaSelectBD($bd, "select idturma, nome from turma $restricao order by nome", $idturma , false);

	    mysqli_close($bd);
	?>

	<?php echo $mensagem; ?>

	<main role="main">
       <div class="container">
        <form class='card p-2' method='POST' action='usuario.php'>

           <h2 class="h3 mb-3 font-weight-normal">Controle de Usuários</h2>
            <input type="hidden" value="<?php echo $id_usuario ?>" name="id_usuario">
            <div class='form-group'>
                <label for='nome' class='float-left'>Nome</label>
                <input class='form-control' type='text' id='nome' name='nome' value="<?php echo $nome; ?>" <?php echo $podeAlterar; ?>>
            </div>

            <div class='form-group'> 
               <label for='cpf' class='float-left'>CPF</label>
                <input type='text' class='form-control' id='cpf' name='cpf' value="<?php echo $cpf; ?>" maxlength='11'>
            </div>

            <div class='form-group'> 
               <label for='senha' class='float-left'>Senha</label>
                <input type='password' class='form-control' id='senha' name='senha' value="<?php echo $senha; ?>">
            </div>

            <div class='form-group'>
            	<label for="tipo" class='float-left mr-2'>Tipo: </label>
				    <select id="tipo" name="tipo" >
				      <?php echo $tipoOpcoes; ?>
				    </select><br>
            </div>

            <div class='form-group'>
            	<label for="turma" class="float-left mr-2">Turma: </label>
				    <select id="idturma" name="idturma" >
				    	<?php echo $turmaOpcoes; ?>
				    </select><br>
           </div>
            
            <div class='form-group'>
                <input type='submit' value='Novo' class="btn btn-secondary">
            	<input type="submit" name="acao" value="<?php echo $descr_acao; ?>" class="btn btn-secondary">
            </div>
            
            
         </form>    
       </div> 
   </main>
	        
	    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
	
	<br>
	
	   <center><legend>Usuários Cadastrados</legend></center>
	   	<main role="main">
   		 <div class="container">
	   <?php
	      echo $tabela;
	   ?>

	   	 </div>
   		</main>

	
</body>

</html>
