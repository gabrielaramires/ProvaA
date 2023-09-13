<?php

  include_once("conectar.php");

  $cpf = mysqli_real_escape_string($bd, $_POST["cpf"]);
  $senha = mysqli_real_escape_string($bd, $_POST["senha"]);
    
  $sql = "select id_usuario, tipo, nome from usuario
          where
             cpf = '$cpf' and
             senha = '$senha' ";
  
  $resultado = mysqli_query($bd, $sql);

  echo mysqli_num_rows($resultado);
  
  if ( mysqli_num_rows($resultado) == 1 ) {
 
      session_start();

      $dados = mysqli_fetch_assoc($resultado);

      $_SESSION["id_usuario"] = $dados["id_usuario"];
      $_SESSION["tipo"]       = $dados["tipo"];
      $_SESSION["nome"]       = $dados["nome"];

      mysqli_close($bd);
      header("location: inicial.php");
     
  } else {
     mysqli_close($bd);
     header("location: index.php?erro=1");
  }

?>

