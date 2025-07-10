<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'conexao.php';
include('protect.php');
include('cadastrarTabaco.php');

$produtor_id = $_SESSION['idprodutor'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $acao = $_POST['acao'] ?? '';
    $periodoSafra = $_POST['periodoEscondido'] ?? '';

    // Verifica produtor e período
    if (!$produtor_id || !$periodoSafra) {
        die("Produtor ou período não informado.");
    }

    // Busca o tabaco_id correspondente
    $sql = "SELECT idtabaco FROM tabaco WHERE periodoSafra = ? AND produtor_idprodutor = ?";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("si", $periodoSafra, $produtor_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        die("Safra não encontrada para este período.");
    }

    $tabaco_id = $res->fetch_assoc()['idtabaco'];

    // Dados da área
    $nome = $_POST['nome'] ?? 'Área sem nome';
    $qtdPes = $_POST['qtdPes'] ?? 0;
    $hectares = $_POST['hectares'] ?? '';
    $dataInicio = $_POST['dataInicio'] ?? '';
    $dataFim = $_POST['dataFim'] ?? '';
    $variedades = $_POST['variedades'] ?? '';
    $produtos = $_POST['produtos'] ?? '';
    $pragas = $_POST['pragasDoencas'] ?? '';
    $agrotoxicos = $_POST['agrotoxicos'] ?? '';
    $mediaFolhas = $_POST['mediaFolhas'] ?? 0;
    $colheitas = $_POST['colheitas'] ?? 0;

    if ($acao === 'cadastrar') {
        $sql = "INSERT INTO area (nome, qtdPes, hectares, dataInicio, dataFim, variedades, produtos, pragasDoencas, agrotoxicos, mediaFolhas, colheitas, tabaco_idtabaco)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param(
            "sissssssssii",
            $nome,
            $qtdPes,
            $hectares,
            $dataInicio,
            $dataFim,
            $variedades,
            $produtos,
            $pragas,
            $agrotoxicos,
            $mediaFolhas,
            $colheitas,
            $tabaco_id
        );

        if ($stmt->execute()) {
            echo " Área cadastrada com sucesso.";
        } else {
            echo " Erro ao salvar a área: " . $stmt->error;
        }
    } else {
        echo "Ação inválida.";
    }
}
?>
