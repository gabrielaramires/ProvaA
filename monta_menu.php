<?php include_once("validar_sessao.php"); ?>

<nav>
<ul class="menu">

	<li><a href="inicial.php">Página Inicial</a></li>

	<?php

		if ($_SESSION["tipo"] == "PA" ) {
			echo '<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">';

          	echo '<a class="navbar-brand h1 mb-0" href="inicial.php">ProvA+</a>';
          	echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          	<span class="navbar-toggler-icon"></span>
          	</button>';

          	echo '<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
              		<li class="nav-item">
                	<a class="nav-link" href="turma.php">Turmas<span class="sr-only">(current)</span></a>
              		</li>';

          	echo '<li class="nav-item">
                 <a class="nav-link" href="usuario.php">Alunos<span class="sr-only">(current)</span></a>
                 </li>';

            echo '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Provas</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">
                      <a class="dropdown-item" href="questao.php">Gerenciar questões</a>
                      <a class="dropdown-item" href="cria_prova.php">Gerenciar provas</a>
                      <a class="dropdown-item" href="monta_prova.php">Montar prova</a>
                      <a class="dropdown-item" href="aluno_prova.php">Vincular alunos e provas</a>
                      <a class="dropdown-item" href="resultados.php">Resultados</a>
                    </div>
             </li> </ul>';
            echo '<ul class="navbar-nav justify-content-end">
                  <li class="nav-item">';
                            echo "<a class='btn btn-outline-success my-2 my-sm-0' href='sair.php'>Sair</a>";
                  echo '</li></ul></div></nav>';
		}

		if ($_SESSION["tipo"] == "PI" ) {
			
			echo "<li><a href='provas.php'>Provas</a></li>";
		}


		if ($_SESSION["tipo"] == "ADM") {

			echo '<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">';

          	echo '<a class="navbar-brand h1 mb-0" href="inicial.php">ProvA+</a>';
          	echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          	<span class="navbar-toggler-icon"></span>
          	</button>';

          	echo '<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
              		<li class="nav-item">
                	<a class="nav-link" href="turma.php">Turmas<span class="sr-only">(current)</span></a>
              		</li>';

          	echo '<li class="nav-item">
                 <a class="nav-link" href="usuario.php">Usuários<span class="sr-only">(current)</span></a>
                 </li></ul>';
                 
            echo '<ul class="navbar-nav justify-content-end">
                  <li class="nav-item">';
                            echo "<a class='btn btn-outline-success my-2 my-sm-0' href='sair.php'>Sair</a>";
                  echo '</li></ul></div></nav>';

		}

		if ($_SESSION["tipo"] == "AA") {
			echo '<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">';

          	echo '<a class="navbar-brand h1 mb-0" href="inicial.php">ProvA+</a>';
          	echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          	<span class="navbar-toggler-icon"></span>
          	</button>';

          	echo '<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
              		<li class="nav-item">
                	<a class="nav-link" href="provas.php">Provas<span class="sr-only">(current)</span></a>
              		</li></ul>';
            	
            echo '<ul class="navbar-nav justify-content-end">
                  <li class="nav-item">';
                            echo "<a class='btn btn-outline-success my-2 my-sm-0' href='sair.php'>Sair</a>";
                  echo '</li></ul></div></nav>';
		}

		if ($_SESSION["tipo"] == "AI") {
			echo "<li><a href='provas.php'>Provas</a></li>";

		}

	?>

	<li><a href="sair.php">Sair </a></li>
	  
	  
</ul>
</nav>

 		<script src="jquery/dist/jquery.js"></script>
		<script src="popper.js/dist/umd/popper.js" ></script>
		<script src="bootstrap/dist/js/bootstrap.js" ></script>
        <script src="ckeditor/ckeditor.js"></script>
