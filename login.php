<?php
session_start();
require 'conexao.php';

// Verifica login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Prepara a busca pelo email
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $usuario = $res->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario;
            header("Location: painel.php");
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Email não encontrado.";
    }
}
?>