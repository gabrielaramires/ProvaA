<!DOCTYPE html>
<html>
<head>
	<title>Prova</title>
	<meta charset="utf-8">
	<link rel='stylesheet' href="./css/estilo.css">
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

		    function nomeProva($bd, $idProva) {
                $sql = "select descricao from prova where idprova = $idProva";
		    	$lista = mysqli_query($bd, $sql);

		        if ( mysqli_num_rows($lista) == 1 ) {
			          $dados = mysqli_fetch_row($lista);
		              return $dados[0];
		         } else 
		              return "";
		    }

		    function nomeUsuario($bd, $id_usuario) {
                $sql = "select nome from usuario where id_usuario = $id_usuario";
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
		    if ( isset($_POST["id_usuario"])) {
		    	$id_usuario = $_POST["id_usuario"];
		    	$nomeUsuario = nomeUsuario($bd,$id_usuario);
		    } else {
		    	$id_usuario = "";
		    	$nomeUsuario = "";
		    }

	        if ($acao == "") 
	            $botao = "listar"; 
	        else {
	        	$ç = array();
	        	if ($acao == "REALIZAR") {

					$sql_inicializar = "insert into resposta (id_usuario, idquestprov, alternativa)

                                        select ap.id_usuario, qp.idquestprov, 0 as alternativa
                                        from  aluno_prova ap, questao_prova qp
                                        where ap.idprova = qp.idprova
                                              and ap.idprova = $idprova
                                              and ap.id_usuario = $id_usuario 
                                              and not exists (select 1 from resposta r where r.id_usuario = ap.id_usuario and qp.idquestprov = r.idquestprov)";

                    mysqli_query($bd, $sql_inicializar);

	        	   $sql_listar = "select  r.*, q.*, 
	        	                  case when r.alternativa = q.alt_certa then 1 else 0 end as acerto
                                  FROM 
                                      resposta r, questao_prova qp, questao q
                                  WHERE r.idquestprov = qp.idquestprov
                                        AND q.idquestao = qp.idquestao
                                        AND qp.idprova = $idprova
                                        AND r.id_usuario = $id_usuario";


									//echo $sql_listar;

                    $prova = mysqli_query($bd, $sql_listar);
	        	
                    if ( mysqli_num_rows($prova) > 0 ) {

                    	    $acertos = 0;

	            	        while ( $dados = mysqli_fetch_assoc($prova)) {

				                   $vIdResposta        = $dados["idresposta"];
				                   $vIdquestprov       = $dados["idquestprov"];
				                   $vIdquestao         = $dados["idquestao"];
				                   $vDescricao         = $dados["descricao"];
				                   $vAlternativa       = $dados["alternativa"];
				                   $vAlt1              = $dados["alt1"];
				                   $vAlt2              = $dados["alt2"];
				                   $vAlt3              = $dados["alt3"];
				                   $vAlt4              = $dados["alt4"];
				                   $vAlt5              = $dados["alt5"];
				                   $vAcerto            = $dados["acerto"];

				                   $nameInput = "resp".$vIdResposta;

				                   $secreto = "";

				                   if ($vAcerto == "1") {
				                   	   /*$secreto = "*";
				                   	   $acertos++;*/
				                   }
				                 
                                   $prova_gerada = $prova_gerada."<h6>$vDescricao</h6>";

                                   $c1 = "";
                                   $c2 = "";
                                   $c3 = "";
                                   $c4 = "";
                                   $c5 = "";

                                   if ($vAlternativa == "1") $c1 = "checked";
                                   if ($vAlternativa == "2") $c2 = "checked";
                                   if ($vAlternativa == "3") $c3 = "checked";
                                   if ($vAlternativa == "4") $c4 = "checked";
                                   if ($vAlternativa == "5") $c5 = "checked";

                                   $prova_gerada = $prova_gerada."
                                   <input type='radio' value = '1' name='$nameInput' $c1>$vAlt1<br>
                                   <input type='radio' value = '2' name='$nameInput' $c2>$vAlt2<br>
                                   <input type='radio' value = '3' name='$nameInput' $c3>$vAlt3<br>
                                   <input type='radio' value = '4' name='$nameInput' $c4>$vAlt4<br>
                                   <input type='radio' value = '5' name='$nameInput' $c5>$vAlt5<br>";
                            } 

                            /*$prova_gerada = $prova_gerada."<hr>Total de acertos: $acertos";*/
                     }
                } 
                else if ($acao == "SALVAR") {

                	foreach ($_POST as $key => $value){

                        if ( substr($key, 0, 4) == "resp") {

                        	$vIdResposta = substr($key, 4);
                        	$vAlternativa = $value;

                        	$sql = "update resposta set alternativa = '$vAlternativa' where idresposta = $vIdResposta;";

                        	//echo $sql."<br>";

                        	mysqli_query($bd, $sql);

                        	//$mensagem = "<script> alert('Prova finalizada')</script>";
                          $mensagem = "Prova finalizada";
                        	//echo "<meta http-equiv='refresh' content='0;URL=inicial.php'>";

                        	$acao = "FINALIZADA";

                         }
                     }
                } 
                else if ($acao == "CONSULTAR") {

					$sql_inicializar = "insert into resposta (id_usuario, idquestprov, alternativa)

                                        select ap.id_usuario, qp.idquestprov, 0 as alternativa
                                        from  aluno_prova ap, questao_prova qp
                                        where ap.idprova = qp.idprova
                                              and ap.idprova = $idprova
                                              and ap.id_usuario = $id_usuario 
                                              and not exists (select 1 from resposta r where r.id_usuario = ap.id_usuario and qp.idquestprov = r.idquestprov)";

                    mysqli_query($bd, $sql_inicializar);

	        	   $sql_listar = "select  r.*, q.*, 
	        	                  case when r.alternativa = q.alt_certa then 1 else 0 end as acerto
                                  FROM 
                                      resposta r, questao_prova qp, questao q
                                  WHERE r.idquestprov = qp.idquestprov
                                        AND q.idquestao = qp.idquestao
                                        AND qp.idprova = $idprova
                                        AND r.id_usuario = $id_usuario";


									//echo $sql_listar;

                    $prova = mysqli_query($bd, $sql_listar);
	        	
                    if ( mysqli_num_rows($prova) > 0 ) {

                    	    $acertos = 0;

	            	        while ( $dados = mysqli_fetch_assoc($prova)) {                             	   

				                   $vIdResposta        = $dados["idresposta"];
				                   $vIdquestprov       = $dados["idquestprov"];
				                   $vIdquestao         = $dados["idquestao"];
				                   $vDescricao         = $dados["descricao"];
				                   $vAlternativa       = $dados["alternativa"];
                           $vAlternativaCerta  = $dados["alt_certa"];
				                   $vAlt1              = $dados["alt1"];
				                   $vAlt2              = $dados["alt2"];
				                   $vAlt3              = $dados["alt3"];
				                   $vAlt4              = $dados["alt4"];
				                   $vAlt5              = $dados["alt5"];
				                   $vAcerto            = $dados["acerto"];


				                   $nameInput = "resp".$vIdResposta;

				                   $secreto = "";

				                   if ($vAcerto == "1") {
				                   	   $secreto = $vAlternativa;
				                   	   $acertos++;
				                   }

                                   $prova_gerada = $prova_gerada."<h6>$vDescricao</h6>";

                                   $c1 = "";
                                   $c2 = "";
                                   $c3 = "";
                                   $c4 = "";
                                   $c5 = "";

                                   $co1 = "";
                                   $co2 = "";
                                   $co3 = "";
                                   $co4 = "";
                                   $co5 = "";

                                   switch ($vAlternativa) {
                                     case '1': $c1 = "checked"; break;
                                     case '2': $c2 = "checked"; break;
                                     case '3': $c3 = "checked"; break;
                                     case '4': $c4 = "checked"; break;
                                     case '5': $c5 = "checked"; break;
                                   }

                                   switch ($vAlternativaCerta) {
                                     case '1': $co1 = "#98FB98"; break;
                                     case '2': $co2 = "#98FB98"; break;
                                     case '3': $co3 = "#98FB98"; break;
                                     case '4': $co4 = "#98FB98"; break;
                                     case '5': $co5 = "#98FB98"; break;
                                   }

                                   if ($vAlternativa != $vAlternativaCerta ) {
                                       switch ($vAlternativa) {
                                         case '1': $co1 = "#E9967A"; break;
                                         case '2': $co2 = "#E9967A"; break;
                                         case '3': $co3 = "#E9967A"; break;
                                         case '4': $co4 = "#E9967A"; break;
                                         case '5': $co5 = "#E9967A"; break;
                                       }
                                   }

                                   $prova_gerada = $prova_gerada."
                                   <span style='background-color: $co1'><input type='radio' value = '1' name='$nameInput' disabled='disabled' $c1>$vAlt1</span><br>
                                   <span style='background-color: $co2'><input type='radio' value = '2' name='$nameInput' disabled='disabled' $c2>$vAlt2</span><br>
                                   <span style='background-color: $co3'><input type='radio' value = '3' name='$nameInput' disabled='disabled' $c3>$vAlt3</span><br>
                                   <span style='background-color: $co4'><input type='radio' value = '4' name='$nameInput' disabled='disabled' $c4>$vAlt4</span><br>
                                   <span style='background-color: $co5'><input type='radio' value = '5' name='$nameInput' disabled='disabled' $c5>$vAlt5</span><br><hr>";
                            } 

                            $prova_gerada = $prova_gerada."Total de acertos: $acertos";

                     }

                }
	        } 

	        mysqli_close($bd);
		?>
	<center>
    <div class="shadow-lg p-3 mb-5 bg-white rounded">
		<h1 class="display-4">Prova:
      

			<?php
			if ($nomeProva != "")
              echo "$nomeProva";
        ?>

        </div>
    	</h1>
	</center>
  <main role='main'>
    <div class='container'>
		  <form action='prova.php' method='post'>
	  	  <?php echo $mensagem; ?>
	 	       <?php
	     	     echo $prova_gerada;
            
            echo "<input type='hidden' id='idprova' name='idprova' value='$idprova'>";
            echo "<input type='hidden' id='id_usuario' name='id_usuario' value='$id_usuario'>";
            echo "<input type='hidden' id='acao' name='acao' value='SALVAR'>";
            
            if ($acao == "REALIZAR"){
                 echo "<br><input type='submit' value='Salvar' class='btn btn-secondary'>";
                 
            }

            echo "</form>";

            if ($acao == "CONSULTAR"){
            	echo "<form action='provas.php' method='post'>";
              echo "<br><input type='submit' value='Voltar para a página anterior' class='btn btn-secondary'>";
              echo "</form>";
            }

            if ($acao == "FINALIZADA"){
              echo "<form action='inicial.php' method='post'>";
              echo "<br><input type='submit' value='Página inicial' class='btn btn-secondary'>";
              echo "</form>";
            }
	  ?>

    </div></main>
</body>
</html>