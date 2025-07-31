<?php
require 'conexao.php';
require 'protect.php';

$idProdutor = $_SESSION['idprodutor'] ?? null;

// --- ADICIONAR TAREFA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descricao'])) {
    $descricao = trim($_POST['descricao']);
    if ($descricao !== '') {
        $sql = "INSERT INTO listatarefas (descricao, conclusao, produtor_idprodutor) VALUES (?, 0, ?)";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param("si", $descricao, $idProdutor);
        $stmt->execute();
   header("Location: paginaInicial.php#lista");
exit;
    }
}

// --- EXCLUIR TAREFA ---
if (isset($_GET['id']) && isset($_GET['excluir'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM listatarefas WHERE idlistaTarefas = ? AND produtor_idprodutor = ?";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("ii", $id, $idProdutor);
    $stmt->execute();
   header("Location: paginaInicial.php#lista");
exit;
}

// --- CONCLUIR/DESCONCLUIR ---
if (isset($_GET['id']) && isset($_GET['toggle'])) {
    $id = (int)$_GET['id'];
    $sql = "UPDATE listatarefas SET conclusao = NOT conclusao WHERE idlistaTarefas = ? AND produtor_idprodutor = ?";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("ii", $id, $idProdutor);
    $stmt->execute();
   header("Location: paginaInicial.php#lista");
exit;
}

// --- EDITAR: EXIBIR FORMULÁRIO ---
if (isset($_GET['id']) && isset($_GET['editar'])) {
    $id = (int)$_GET['id'];
    $stmt = $conecta->prepare("SELECT descricao FROM listatarefas WHERE idlistaTarefas = ? AND produtor_idprodutor = ?");
    $stmt->bind_param("ii", $id, $idProdutor);
    $stmt->execute();
    $result = $stmt->get_result();
    $tarefa = $result->fetch_assoc();

    echo '<form method="post" action="cadastrarTarefa.php?id=' . $id . '&salvar=1">';
    echo '<input type="text" name="nova_descricao" value="' . htmlspecialchars($tarefa['descricao']) . '">';
    echo '<button type="submit">Salvar</button>';
    echo '</form>';
   header("Location: paginaInicial.php#lista");
exit;
}

// --- SALVAR NOVA DESCRIÇÃO ---
if (isset($_GET['id']) && isset($_GET['salvar']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_GET['id'];
    $novaDescricao = trim($_POST['nova_descricao']);
    if ($novaDescricao !== '') {
        $sql = "UPDATE listatarefas SET descricao = ? WHERE idlistaTarefas = ? AND produtor_idprodutor = ?";
        $stmt = $conecta->prepare($sql);
        $stmt->bind_param("sii", $novaDescricao, $id, $idProdutor);
        $stmt->execute();
    }
    header("Location: paginaInicial.php#lista");
exit;
}

// --- BUSCAR TAREFAS E CALCULAR PROGRESSO ---
$sql = "SELECT * FROM listatarefas WHERE produtor_idprodutor = ?";
$stmt = $conecta->prepare($sql);
$stmt->bind_param("i", $idProdutor);
$stmt->execute();
$result = $stmt->get_result();

$tarefas = [];
while ($row = $result->fetch_assoc()) {
    $tarefas[] = $row;
}

$total = count($tarefas);
$concluidas = count(array_filter($tarefas, fn($t) => $t['conclusao']));
$progresso = $total > 0 ? ($concluidas / $total) * 100 : 0;
?>
