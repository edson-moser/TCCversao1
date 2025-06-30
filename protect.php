<?php
if(!isset($_SESSION)){
    session_start();

}
if(!isset($_SESSION['idprodutor'])){
    die("Você não pode acessar esta página porque não está logado.<p><a href=\"LoginCadastro.php\">ENTRAR</a></p>");
}
?>