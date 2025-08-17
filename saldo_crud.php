<?php
require_once "conexao.php"; 

header("Content-Type: application/json");

$acao = $_POST["acao"] ?? $_GET["acao"] ?? null;

if (!$acao) {
    echo json_encode(["erro" => "Nenhuma ação informada"]);
    exit;
}

switch ($acao) {
    case "criar":
        $valor = $_POST["valor"];
        $sinal = $_POST["sinal"]; 
        $descricao = $_POST["descricao"];
        $dataOperacao = $_POST["data"];
        $cultura = $_POST["cultura"];
        $categoria = $_POST["categoria"];
        $produtor_id = $_POST["produtor_id"]; 

        $sql = "INSERT INTO transacao (valor, sinal, descricao, dataOperacao, produtor_idprodutor, culturas, seletor) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $ok = $stmt->execute([$valor, $sinal, $descricao, $dataOperacao, $produtor_id, $cultura, $categoria]);

        echo json_encode(["sucesso" => $ok]);
        break;

    case "listar":
        $produtor_id = $_GET["produtor_id"]; 
        $sql = "SELECT * FROM transacao WHERE produtor_idprodutor = ? ORDER BY dataOperacao DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$produtor_id]);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($dados);
        break;

    case "editar":
        $id = $_POST["id"];
        $valor = $_POST["valor"];
        $sinal = $_POST["sinal"];
        $descricao = $_POST["descricao"];
        $dataOperacao = $_POST["data"];
        $cultura = $_POST["cultura"];
        $categoria = $_POST["categoria"];

        $sql = "UPDATE transacao 
                   SET valor=?, sinal=?, descricao=?, dataOperacao=?, culturas=?, seletor=? 
                 WHERE idtransacao=?";
        $stmt = $conn->prepare($sql);
        $ok = $stmt->execute([$valor, $sinal, $descricao, $dataOperacao, $cultura, $categoria, $id]);

        echo json_encode(["sucesso" => $ok]);
        break;

    case "excluir":
        $id = $_POST["id"];
        $sql = "DELETE FROM transacao WHERE idtransacao=?";
        $stmt = $conn->prepare($sql);
        $ok = $stmt->execute([$id]);

        echo json_encode(["sucesso" => $ok]);
        break;

    default:
        echo json_encode(["erro" => "Ação inválida"]);
}
