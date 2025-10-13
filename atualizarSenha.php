<?php
session_start();
if(!isset($_SESSION['idprodutor'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Atualizar Senha</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans+SC:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    background-color: #F5F1E6; /* bege claro */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

form {
    background: #ffffff; /* branco */
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    width: 320px;
}

input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    color: #4B3B2A; /* marrom do texto */
}

input::placeholder {
    color: #a28c78; /* tom suave de bege */
}

button {
    width: 100%;
    padding: 10px;
    background-color: #57a773; /* verde claro */
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-family: 'Alegreya Sans SC', sans-serif;
    font-weight: bold;
    font-size: 15px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #006837; /* verde escuro */
}

</style>
</head>
<body>

<form action="processaAtualizarSenha.php" method="POST">
    <h3>Atualizar Senha</h3>

    <label>Senha atual:</label>
    <input type="password" name="senhaAtual" required>

    <label>Nova senha:</label>
    <input type="password" name="novaSenha" required>

    <label>Confirmar nova senha:</label>
    <input type="password" name="confirmaSenha" required>

    <button type="submit">Atualizar</button>
</form>

</body>
</html>
