<?php include_once("validar_sessao.php"); ?>
<!DOCTYPE html>

<html>

<head>
	<title>Cadastro de Questões</title>
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
	    
	    $idquestao        = "";
	    $descricao        = "";
	    $alt1			  = "";
	    $alt2			  = "";
	    $alt3			  = "";
	    $alt4			  = "";
	    $alt5			  = "";
	    $alt_certa		  = "";   
	    
	    $podeAlterar = "";
	    
	    if ( ! isset($_POST["acao"] ) ) {
	        $descr_acao = "Incluir";
		} else {
			 $acao = strtoupper($_POST["acao"]);
			 
			 if ( $acao == "INCLUIR" || $acao == "SALVAR" ) {

				$idquestao           = mysqli_real_escape_string($bd, $_POST["idquestao"]);
				$descricao 			 = mysqli_real_escape_string($bd, $_POST["descricao"]);
				$alt1                = mysqli_real_escape_string($bd, $_POST["alt1"]);
				$alt2                = mysqli_real_escape_string($bd, $_POST["alt2"]);
				$alt3                = mysqli_real_escape_string($bd, $_POST["alt3"]);
				$alt4                = mysqli_real_escape_string($bd, $_POST["alt4"]);
				$alt5                = mysqli_real_escape_string($bd, $_POST["alt5"]);
				$alt_certa           = mysqli_real_escape_string($bd, $_POST["alt_certa"]);
			 }
			 
			 if ( $acao == "SALVAR" || $acao == "EXCLUIR" || $acao == "BUSCAR") { 
			    //Chave(s) primária(s)
			    $idquestao = $_POST["idquestao"];
			 }
			 
			 if ( $acao == "INCLUIR") {
				 
                $id_usuario = $_SESSION["id_usuario"];

			    $sql = "insert into questao 
			             (descricao, alt1, alt2, alt3, alt4, alt5, alt_certa, id_usuario)
			            values (
			             '$descricao', 
			             '$alt1',  
			             '$alt2',
			             '$alt3',
			             '$alt4',
			             '$alt5',
			             '$alt_certa',
			             $id_usuario
			         )";
			    
			    
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
			    	//$idquestao = mysqli_insert_id($bd);
	                    $idquestao        = "";
					    $descricao        = "";
					    $alt1			  = "";
					    $alt2			  = "";
					    $alt3			  = "";
					    $alt4			  = "";
					    $alt5			  = "";
					    $alt_certa		  = ""; 

				}
				 
			 } else if (strtoupper($acao) == "SALVAR") {
				 
				 $descr_acao = "Salvar";
				 
				 $sql = " update questao 
				          set
				            descricao = '$descricao',
				            alt1 = '$alt1',
				            alt2 = '$alt2',
				            alt3 = '$alt3',
				            alt4 = '$alt4',
				            alt5 = '$alt5',
				            alt_certa = '$alt_certa'
				          where
				            idquestao = $idquestao";
				            
				 if ( ! mysqli_query($bd, $sql) ) {
					
					if ( mysqli_errno($bd) == 1062 ) {
						$mensagem = "<h3>Não foi possível fazer a inclusão (dados duplicados)</h3>";
					} else {
						$mensagem = "<h3>Ocorreu um erro ao 
						alterar os dados: </h3> 
						<h3>".mysqli_error($bd)."</h3>".$sql. 						
						"<h4>".mysqli_errno($bd)."</h4>";
				    }
				} else {
					    $idquestao        = "";
					    $descricao        = "";
					    $alt1			  = "";
					    $alt2			  = "";
					    $alt3			  = "";
					    $alt4			  = "";
					    $alt5			  = "";
					    $alt_certa		  = ""; 

				}         
			 } else if (strtoupper($acao) == "EXCLUIR") {
				 
				 $descr_acao = "Incluir";
				  
				 $sql = "delete from questao where idquestao = $idquestao";
				
				 if ( ! mysqli_query($bd, $sql) ) { 
                      $mensagem = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
  					<strong>Não foi possível excluir essa questão (ela está vinculada em alguma prova)</strong>
  					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    					<span aria-hidden='true'>&times;</span>
 						 </button>
						</div>";
				 }
				 
				 
			 } else if (strtoupper($acao) == "BUSCAR") {
				 
				 $descr_acao = "Salvar";
				 
				 $sql = "select * from questao where idquestao = $idquestao";
				 
				 $resultado = mysqli_query($bd, $sql);
				 
				 if ( mysqli_num_rows($resultado) == 1 ) {
					 
					$dados = mysqli_fetch_assoc($resultado);
					 
					$idquestao        = $dados["idquestao"];
					$descricao        = $dados["descricao"];
					$alt1             = $dados["alt1"];
					$alt2             = $dados["alt2"];
					$alt3             = $dados["alt3"];
					$alt4             = $dados["alt4"];
					$alt5             = $dados["alt5"];
					$alt_certa        = $dados["alt_certa"];
					 
				 }
				 
				 
			 }
		}

		if ( $_SESSION["tipo"] == 'ADM')
			$sql_extra = "";
		else 
			$sql_extra = " where id_usuario = ".$_SESSION["id_usuario"];
		
		$sql_listar = "select * 
		               from questao $sql_extra";
		
		$lista = mysqli_query($bd, $sql_listar);
		
		if ( mysqli_num_rows($lista) > 0 ) {
			
			echo "<div class='table-responsive'>";
                    $tabela = "<table class='table table-striped'>";
                    $tabela = $tabela."<thead style='font-size: 15px'><tr><th scope='col'>Descrição</th><th scope='col'>1ªAlternativa</th><th scope='col'>2ª Alternativa</th><th scope='col'>3ª Alternativa</th><th scope='col'>4ª Alternativa</th><th scope='col'>5ª Alternativa</th><th scope='col'>Alternativa Certa</th><th scope='col'>Editar</th><th scope='col'>Excluir</th></tr></thead><tbody>";
			
			while ( $dados = mysqli_fetch_assoc($lista)) {
					$vIdquestao        = $dados["idquestao"];
					$vDescricao        = $dados["descricao"];
					$vAlt1             = $dados["alt1"];
					$vAlt2             = $dados["alt2"];
					$vAlt3             = $dados["alt3"];
					$vAlt4             = $dados["alt4"];
					$vAlt5             = $dados["alt5"];
					$vAlt_certa        = $dados["alt_certa"];
				
				$alterar = "<form method='post'>
					            <input type='hidden' name='idquestao' value='$vIdquestao'>
					            <input type='hidden' name='acao' value='BUSCAR'>
					            <input type='image' src='./img/alterar.png' value='submit'>
					            </form>";

				$excluir = "<form method='post'>
					            <input type='hidden' name='idquestao' value='$vIdquestao'>
					            <input type='hidden' name='acao' value='EXCLUIR'>
					            <input type='image' src='./img/excluir.png' value='submit'>
					            </form>";
				            
				$tabela = $tabela."<tr><td>$vDescricao</td><td>$vAlt1</td><td>$vAlt2</td><td>$vAlt3</td><td>$vAlt4</td><td>$vAlt5</td><td>$vAlt_certa</td><td>$alterar</td><td>$excluir</td></tr>";
				            
			}
			
			$tabela = $tabela."</tbody></table></div>";
			
		} else {
			$tabela = "<div class='alert alert-danger' role='alert'> Não há questões para listar</div>";
		}
		
		
		$alt_certaVal    = array("1","2", "3", "4", "5");
		$alt_certaDescr  = array("1ª Alternativa ","2ª Alternativa", "3ª Alternativa", "4ª Alternativa", "5ª Alternativa");
		$alt_certaOpcoes = montaSelect($alt_certaVal, $alt_certaDescr, $alt_certa, false); 
		

	    mysqli_close($bd);
	?>
	
	<?php echo $mensagem; ?>

	<main role="main">
       <div class="container">
        <form class='card p-2' method='POST' action='questao.php'>

           <h2 class="h3 mb-3 font-weight-normal">Controle de Questões</h2>
            <input type="hidden" value="<?php echo $id_usuario ?>" name="id_usuario">

            <p class='text-left mb-2'>Descrição</p>
                <div class='form-group'>
                    <textarea class='form-control ckeditor' id='descricao' name='descricao' <?php echo $podeAlterar; ?>><?php echo $descricao; ?></textarea>
                </div>

                <script>
                  CKEDITOR.replace( 'descricao' );
                </script>


            <div class='form-group'> 
               <label for='alt1' class='float-left'>1ª Alternativa</label>
                <input type='text' class='form-control' id='alt1' name='alt1' value="<?php echo $alt1; ?>">
            </div>

            <div class='form-group'> 
               <label for='alt2' class='float-left'>2ª Alternativa</label>
                <input type='text' class='form-control' id='alt2' name='alt2' value="<?php echo $alt2; ?>">
            </div>

            <div class='form-group'> 
               <label for='alt3' class='float-left'>3ª Alternativa</label>
                <input type='text' class='form-control' id='alt3' name='alt3' value="<?php echo $alt3; ?>">
            </div>

            <div class='form-group'> 
               <label for='alt4' class='float-left'>4ª Alternativa</label>
                <input type='text' class='form-control' id='alt4' name='alt4' value="<?php echo $alt4; ?>">
            </div>

            <div class='form-group'> 
               <label for='alt5' class='float-left'>5ª Alternativa</label>
                <input type='text' class='form-control' id='alt5' name='alt5' value="<?php echo $alt5; ?>">
            </div>

            <div class='form-group'>
            	<label for="alt_certa" class='float-left mr-2'>Alternativa certa: </label>
				    <select id="alt_certa" name="alt_certa" >
				      <?php echo $alt_certaOpcoes; ?>
				    </select><br>
            </div>
   
            <div class='form-group'>
                <input type='submit' value='Novo' class="btn btn-secondary">
            	<input type="submit" name="acao" value="<?php echo $descr_acao; ?>" class="btn btn-secondary">
            </div>
            
           <input type="hidden" name="idquestao" value="<?php echo $idquestao; ?>"> 
         </form>    
       </div> 
   </main>   
	        
	<br>
	
	<center><legend>Questões Cadastradas</legend></center>
	<main role="main">
   		 <div>
	   
	   <?php
	      echo $tabela;
	   ?>
	   </div>
   		</main>
	
</body>

</html>
