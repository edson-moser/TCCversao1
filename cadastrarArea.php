<?php
require('conexao.php');
include('protect.php');
include('cadastrarTabaco.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produtor_id = $_SESSION['idprodutor'] ?? null;
    $periodoSafra = $_POST['periodoEscondido'] ?? null;

    $sql = "SELECT idtabaco FROM tabaco WHERE periodoSafra = ? AND produtor_idprodutor = ?";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("si", $periodoSafra, $produtor_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        die("Safra não encontrada para este período.");
    }

    $tabaco = $res->fetch_assoc();
    $tabaco_id = $tabaco['idtabaco'];

    // Pegando o nome do botão
    $nome = $_POST['nome'] ?? 'Área sem nome';
    $qtdPes = $_POST['qtdPes'] ?? 0;
    $hectares = $_POST['hectares'] ?? '';
    $dataInicio=$_POST['dataInicio'] ?? '';
    $dataFim = $_POST['dataFim'] ?? '';
    $variedades = $_POST['variedades'] ?? '';
    $produtos = $_POST['produtos'] ?? '';
    $pragas = $_POST['pragasDoencas'] ?? '';
    $agrotoxicos = $_POST['agrotoxicos'] ?? '';
    $mediaFolhas = $_POST['mediaFolhas'] ?? 0;
    $colheitas = $_POST['colheitas'] ?? 0;

    // Inserção
    $sql = "INSERT INTO area (nome, qtdPes, hectares,dataInicio, dataFim, variedades, produtos, pragasDoencas, agrotoxicos, mediaFolhas, colheitas, tabaco_idtabaco)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("sissssssiiii", $nome, $qtdPes, $hectares,$dataInicio, $dataFim, $variedades, $produtos, $pragas, $agrotoxicos, $mediaFolhas, $colheitas, $tabaco_id);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Área cadastrada com sucesso.</p>";
    } else {
        echo "<p style='color:red;'>Erro ao salvar a área.</p>";
    }
}
?>
