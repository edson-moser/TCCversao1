<?php
require "conexao.php";

header('Content-Type: application/json');

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

if ($acao === "criar") {
    // Criar nova transação
    $produtor_id = intval($_POST['produtor_id'] ?? 0);
    $valor = floatval(str_replace(',', '.', $_POST['valor'] ?? 0));
    $sinal = (isset($_POST['sinal']) && $_POST['sinal'] === '-') ? '-' : '+';
    $descricao = $_POST['descricao'] ?? '';
    $data = $_POST['data'] ?? null;
    $cultura = $_POST['cultura'] ?? '';
    $seletor = $_POST['seletor'] ?? '';

    // validações básicas
    if ($produtor_id <= 0 || $valor == 0 || empty($descricao) || empty($data)) {
        http_response_code(400);
        echo json_encode(["sucesso" => false, "erro" => "Parâmetros inválidos ou incompletos."]);
        exit;
    }

    $sql = "INSERT INTO transacao (valor, sinal, descricao, dataOperacao, produtor_idprodutor, culturas, seletor) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conecta->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["sucesso" => false, "erro" => "Erro ao preparar statement: " . $conecta->error]);
        exit;
    }

    // tipos: d = double, s = string, i = int
    $bindOk = $stmt->bind_param("dsssiss", $valor, $sinal, $descricao, $data, $produtor_id, $cultura, $seletor);
    if (!$bindOk) {
        http_response_code(500);
        echo json_encode(["sucesso" => false, "erro" => "Erro ao bindar parâmetros: " . $stmt->error]);
        exit;
    }

    $ok = $stmt->execute();
    if ($ok) {
        echo json_encode(["sucesso" => true, "id" => $conecta->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(["sucesso" => false, "erro" => $stmt->error]);
    }
    $stmt->close();
    exit;
}

elseif ($acao === "listar") {
    // Listar transações de um produtor
    $produtor_id = intval($_GET['produtor_id'] ?? 0);
    if ($produtor_id <= 0) {
        http_response_code(400);
        echo json_encode(["sucesso" => false, "erro" => "produtor_id inválido"]);
        exit;
    }

    $sql = "SELECT * FROM transacao WHERE produtor_idprodutor = ? ORDER BY dataOperacao DESC";
    $stmt = $conecta->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["sucesso" => false, "erro" => "Erro ao preparar statement: " . $conecta->error]);
        exit;
    }
    $stmt->bind_param("i", $produtor_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $dados = $resultado->fetch_all(MYSQLI_ASSOC);
    echo json_encode($dados);
    $stmt->close();
    exit;
}

elseif ($acao === "editar") {
    // Editar transação
    $id = intval($_POST['id'] ?? 0);
    $valor = floatval(str_replace(',', '.', $_POST['valor'] ?? 0));
    $sinal = (isset($_POST['sinal']) && $_POST['sinal'] === '-') ? '-' : '+';
    $descricao = $_POST['descricao'] ?? '';
    $data = $_POST['data'] ?? null;
    $cultura = $_POST['cultura'] ?? '';
    $seletor = $_POST['seletor'] ?? '';

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["sucesso" => false, "erro" => "ID inválido."]);
        exit;
    }

    $sql = "UPDATE transacao 
            SET valor=?, sinal=?, descricao=?, dataOperacao=?, culturas=?, seletor=? 
            WHERE idtransacao=?";
    $stmt = $conecta->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["sucesso" => false, "erro" => "Erro ao preparar statement: " . $conecta->error]);
        exit;
    }

    $bindOk = $stmt->bind_param("dsssssi", $valor, $sinal, $descricao, $data, $cultura, $seletor, $id);
    if (!$bindOk) {
        http_response_code(500);
        echo json_encode(["sucesso" => false, "erro" => "Erro ao bindar parâmetros: " . $stmt->error]);
        exit;
    }

    $ok = $stmt->execute();
    if ($ok) {
        echo json_encode(["sucesso" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["sucesso" => false, "erro" => $stmt->error]);
    }
    $stmt->close();
    exit;
}

elseif ($acao === "deletar") {
    // Deletar transação
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["sucesso" => false, "erro" => "ID inválido."]);
        exit;
    }

    // OBS: aqui usamos idtransacao (nome do PK na tabela 'transacao')
    $sql = "DELETE FROM transacao WHERE idtransacao=?";
    $stmt = $conecta->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["sucesso" => false, "erro" => "Erro ao preparar statement: " . $conecta->error]);
        exit;
    }
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();

    if ($ok) {
        echo json_encode(["sucesso" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["sucesso" => false, "erro" => $stmt->error]);
    }
    $stmt->close();
    exit;
}

else {
    http_response_code(400);
    echo json_encode(["sucesso" => false, "erro" => "Ação inválida"]);
    exit;
}



/*$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

if ($acao === "criar") {
    // Criar nova transação
    $produtor_id = intval($_POST['produtor_id']);
    $valor = floatval($_POST['valor']);
    $sinal = $_POST['sinal'] === '-' ? '-' : '+';
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];
    $cultura = $_POST['cultura'];
    $seletor = $_POST['seletor']; 

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
    $sql = "DELETE FROM transacao WHERE idsaldo=?";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();

    echo json_encode(["sucesso" => $ok]);
}

else {
    echo json_encode(["erro" => "Ação inválida"]);
}*/
