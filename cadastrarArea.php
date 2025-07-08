<?php

    require 'conexao.php';
include('protect.php');
include('cadastrarTabaco.php');




// Obtem o tabaco_id passado via GET ou POST
$tabaco_id = $_GET['tabaco'] ?? $_POST['tabaco_idtabaco'] ?? null;
if (!$tabaco_id) {
    die("ID do tabaco não fornecido.");
}

// Inicializa dados
$dados = [
    'idarea' => '',
    'qtdPes' => '',
    'hectares' => '',
    'dataFim' => '',
    'variedades' => '',
    'produtos' => '',
    'pragasDoencas' => '',
    'agrotoxicos' => '',
    'mediaFolhas' => '',
    'colheitas' => '',
];

// Se for edição
if (isset($_GET['id'])) {
    $area_id = $_GET['id'];
    $sql = "SELECT * FROM area WHERE idarea = ? AND tabaco_idtabaco = ?";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("ii", $area_id, $tabaco_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows) {
        $dados = $res->fetch_assoc();
    } else {
        echo "Área não encontrada.";
        exit;
    }
}

// Se for envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $area_id = $_POST['idarea'] ?? null;
    $qtdPes = $_POST['qtdPes'] ?? 0;
    $hectares = $_POST['hectares'] ?? '';
    $dataFim = $_POST['dataFim'] ?? '';
    $variedades = $_POST['variedades'] ?? '';
    $produtos = $_POST['produtos'] ?? '';
    $pragas = $_POST['pragasDoencas'] ?? '';
    $agrotoxicos = $_POST['agrotoxicos'] ?? '';
    $mediaFolhas = $_POST['mediaFolhas'] ?? 0;
    $colheitas = $_POST['colheitas'] ?? 0;

    if ($area_id) {
        // Atualiza
        $sql = "UPDATE area SET qtdPes = ?, hectares = ?, dataFim = ?, variedades = ?, produtos = ?, pragasDoencas = ?, agrotoxicos = ?, mediaFolhas = ?, colheitas = ?
                WHERE idarea = ? AND tabaco_idtabaco = ?";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param("issssssiiii", $qtdPes, $hectares, $dataFim, $variedades, $produtos, $pragas, $agrotoxicos, $mediaFolhas, $colheitas, $area_id, $tabaco_id);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Área atualizada com sucesso.</p>";
            $dados = $_POST;
            $dados['idarea'] = $area_id;
        } else {
            echo "<p style='color:red;'>Erro ao atualizar a área.</p>";
        }
    } else {
        // Inserção
        $sql = "INSERT INTO area (qtdPes, hectares, dataFim, variedades, produtos, pragasDoencas, agrotoxicos, mediaFolhas, colheitas, tabaco_idtabaco)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param("issssssiii", $qtdPes, $hectares, $dataFim, $variedades, $produtos, $pragas, $agrotoxicos, $mediaFolhas, $colheitas, $tabaco_id);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Área cadastrada com sucesso.</p>";
            $nova_id = $stmt->insert_id;
            $dados = $_POST;
            $dados['idarea'] = $nova_id;
        } else {
            echo "<p style='color:red;'>Erro ao salvar a área.</p>";
        }
    }
}

?>