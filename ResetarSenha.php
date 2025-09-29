<?php
$conn = new mysqli('localhost', 'root', 'root', 'naturis');

$token = isset($_GET['token']) ? $_GET['token'] : '';
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaSenha = $_POST['nova_senha'];
    $token = $_POST['token'];

    // Verifica token e expiração
    $stmt = $conn->prepare("SELECT id, token_expira FROM produtor WHERE token_recuperacao = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($id, $expira);
    if ($stmt->fetch() && strtotime($expira) > time()) {
        $stmt->close();
        // Atualiza senha e limpa token
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt2 = $conn->prepare("UPDATE produtor SET senha = ?, token_recuperacao = NULL, token_expira = NULL WHERE id = ?");
        $stmt2->bind_param("si", $hash, $id);
        $stmt2->execute();
        $stmt2->close();
        $sucesso = "Senha redefinida com sucesso!";
    } else {
        $erro = "Token inválido ou expirado.";
    }
} else if ($token) {
    // Verifica se token existe e não expirou
    $stmt = $conn->prepare("SELECT token_expira FROM produtor WHERE token_recuperacao = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($expira);
    if (!$stmt->fetch() || strtotime($expira) < time()) {
        $erro = "Token inválido ou expirado.";
    }
    $stmt->close();
} else {
    $erro = "Token não informado.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<link rel="stylesheet" href="ResetarSenha.css">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
</head>
<body>
    <h1>Redefinir Senha</h1>
    <?php if ($erro): ?>
        <p style="color:red"><?= $erro ?></p>
    <?php elseif ($sucesso): ?>
        <p style="color:green"><?= $sucesso ?></p>
        <a href="login.html">Ir para login</a>
    <?php elseif ($token): ?>
        <form method="post">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <label>Nova senha:</label>
            <input type="password" name="nova_senha" required>
            <button type="submit">Salvar nova senha</button>
        </form>
    <?php endif; ?>
</body>
</html>