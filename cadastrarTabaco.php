<?php
require 'conexao.php';
include('protect.php');

$produtor_id = $_SESSION['idprodutor'] ?? null;
$dados = [
    'idtabaco' => '',
    'periodoSafra' => '',
    'total' => '',
    'precoTotal' => '',
    'kilos' => '',
    'estufadas' => '',
    'totalHectares' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && $produtor_id) {
    // Dados do formulário
    $tabaco_id = $_POST['tabaco_idtabaco'] ?? null;
    $periodo = $_POST['periodoSafra'] ?? '';
    $total_plantado = floatval($_POST['total'] ?? 0);
    $precoTotal = floatval($_POST['precoTotal'] ?? 0);
    $kilos = floatval($_POST['kilos'] ?? 0);
    $estufadas = intval($_POST['estufadas'] ?? 0);
    $hectares = floatval($_POST['totalHectares'] ?? 0);

    // Verifica se já existe safra para esse período
    $sqlVerifica = "SELECT idtabaco FROM tabaco WHERE periodoSafra = ? AND produtor_idprodutor = ?";
    $stmt = $conecta->prepare($sqlVerifica);
    $stmt->bind_param("si", $periodo, $produtor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Já existe: atualizar
        $row = $result->fetch_assoc();
        $tabaco_id = $row['idtabaco'];

        $sqlUpdate = "UPDATE tabaco SET total = ?, estufadas = ?, kilos = ?, totalHectares = ?, precoTotal = ?, periodoSafra = ?
                      WHERE idtabaco = ? AND produtor_idprodutor = ?";
        $stmt = $conecta->prepare($sqlUpdate);
        $stmt->bind_param("iiddssii", $total_plantado, $estufadas, $kilos, $hectares, $precoTotal, $periodo, $tabaco_id, $produtor_id);

        if ($stmt->execute()) {
            // Preenche os dados para exibir no formulário
            $dados = [
                'idtabaco' => $tabaco_id,
                'periodoSafra' => $periodo,
                'total' => $total_plantado,
                'precoTotal' => $precoTotal,
                'kilos' => $kilos,
                'estufadas' => $estufadas,
                'totalHectares' => $hectares
            ];
            header("Location: tabaco.php");
            exit;
        } else {
            echo "<p style='color:red;'>Erro ao atualizar.</p>";
        }
    } else {
        // Não existe: inserir novo
        $sqlInsert = "INSERT INTO tabaco (total, estufadas, kilos, totalHectares, precoTotal, produtor_idprodutor, periodoSafra)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conecta->prepare($sqlInsert);
        $stmt->bind_param("iidddis", $total_plantado, $estufadas, $kilos, $hectares, $precoTotal, $produtor_id, $periodo);

        if ($stmt->execute()) {
            $novo_id = $stmt->insert_id;
            $dados = [
                'idtabaco' => $novo_id,
                'periodoSafra' => $periodo,
                'total' => $total_plantado,
                'precoTotal' => $precoTotal,
                'kilos' => $kilos,
                'estufadas' => $estufadas,
                'totalHectares' => $hectares
            ];
            header("Location: tabaco.php");
            exit;
        } else {
            echo "<p style='color:red;'>Erro ao salvar.</p>";
        }
    }
}
?>
