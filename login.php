
<?php
include('conexao.php');
if(isset($_POST['email'])|| isset($_POST['senha'])){
   if(strlen($_POST['email'])==0){
    echo"Preencha seu e-mail";
   }else if(strlens($_POST['senha'])==0){
    echo"Preencha sua senha";
   }else{

    $email= mysqli->real_escape_string($_POST['email']);
    $senha= mysqli->real_escape_string($_POST['senha']);

   $sql_code= "SELECT * FROM produtor where email= '$email' and senha='$senha'";
   $sql_query= $mysql->query($sql_code) or die("Falha na execussão no código SQL:" . mysqli->error);
   $quantidade = $sql_query->num_rows;

 if($quantidade==1){
    $usuario= $sql_query->fetch_assoc();

    if(!isset($_SESSION)){
        session_start();

    }
    $_SESSION['idprodutor']= $usuario['id'];
    $_SESSION['nome']= $usuario['nome'];

    header("Location: paginaInicial.php");
 }else{
    echo"Falha ao logar! E-mail ou senha incorretos";
    

   }
   } 
}
?>