 <?php
// Turn off all error reporting
error_reporting(0);

// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Reporting E_NOTICE can be good too (to report uninitialized
// variables or catch variable name misspellings ...)
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);

// Report all PHP errors
error_reporting(E_ALL);



// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
include('conexao.php');



    $email= $_POST['email'];
    $senha=  $_POST['senha'];

    $sql_code= "SELECT * FROM produtor where email= '$email' limit 1";
    $sql_exec=$conecta->query($sql_code) or die($conecta->error);

   
    $usuario= $sql_exec->fetch_assoc();
 if(password_verify($senha,$usuario['senha'])){

    if(!isset($_SESSION)){
        session_start();

    }
    $_SESSION['idprodutor']= $usuario['idprodutor'];
    $_SESSION['nome']= $usuario['nome'];

    header("Location: paginaInicial.php");
 }else{
    echo"Falha ao logar! E-mail ou senha incorretos";
    

   }
   

?>