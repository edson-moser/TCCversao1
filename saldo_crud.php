<?php
require "conexao.php";

header('Content-Type: application/json');

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

if ($acao === "criar") {
    // Criar nova transação
    $produtor_id = intval($_POST['produtor_id']);
    $valor = floatval($_POST['valor']);
    $sinal = $_POST['sinal'] === '-' ? '-' : '+';
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];
    $cultura = $_POST['cultura'];
    $seletor = $_POST['seletor']; // substitui categoria

    $sql = "INSERT INTO transacao (valor, sinal, descricao, dataOperacao, produtor_idprodutor, culturas, seletor) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("dsssiss", $valor, $sinal, $descricao, $data, $produtor_id, $cultura, $seletor);
    $ok = $stmt->execute();

    echo json_encode(["sucesso" => $ok, "id" => $conecta->insert_id]);
}

elseif ($acao === "listar") {
    // Listar transações de um produtor
    $produtor_id = intval($_GET['produtor_id']);
    $sql = "SELECT * FROM transacao WHERE produtor_idprodutor = ? ORDER BY dataOperacao DESC";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("i", $produtor_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $dados = $resultado->fetch_all(MYSQLI_ASSOC);

    echo json_encode($dados);
}

elseif ($acao === "editar") {
    // Editar transação
    $id = intval($_POST['id']);
    $valor = floatval($_POST['valor']);
    $sinal = $_POST['sinal'] === '-' ? '-' : '+';
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];
    $cultura = $_POST['cultura'];
    $seletor = $_POST['seletor'];

    $sql = "UPDATE transacao 
            SET valor=?, sinal=?, descricao=?, dataOperacao=?, culturas=?, seletor=? 
            WHERE idtransacao=?";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("dsssssi", $valor, $sinal, $descricao, $data, $cultura, $seletor, $id);
    $ok = $stmt->execute();

    echo json_encode(["sucesso" => $ok]);
}

elseif ($acao === "deletar") {
    // Deletar transação
    $id = intval($_POST['id']);
    $sql = "DELETE FROM transacao WHERE idtransacao=?";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();

    echo json_encode(["sucesso" => $ok]);
}

else {
    echo json_encode(["erro" => "Ação inválida"]);
}
