<?php
include('protect.php');
include('conexao.php');

$produtor_id = $_SESSION['idprodutor'] ?? null;
$periodoSelecionado = $_POST['periodoSelecionado'] ?? null;

if ($produtor_id && $periodoSelecionado) {
    $sql = "SELECT * FROM area 
            WHERE tabaco_idtabaco = (
                SELECT idtabaco 
                FROM tabaco 
                WHERE periodoSafra = ? 
                  AND produtor_idprodutor = ?
            )";

    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("si", $periodoSelecionado, $produtor_id);
    $stmt->execute();
    $areasEdicao = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach ($areasEdicao as $a) {
        echo '<option value="'.$a['idarea'].'">'.htmlspecialchars($a['nome']);
        if (!empty($a['metodo'])) {
            echo ' - ' . htmlspecialchars($a['metodo']);
        }
        echo '</option>';
    }
}
