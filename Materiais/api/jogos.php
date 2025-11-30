<?php
session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

// Verificar se é admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado']);
    exit;
}

$pdo = getConnection();
if (!$pdo) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao conectar ao banco de dados']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Listar todos os jogos
            // Verificar se as colunas de jogos semelhantes existem
            $columns = $pdo->query("SHOW COLUMNS FROM jogos LIKE 'jogo_semelhante_1'")->fetchAll();
            $hasSemelhantes = count($columns) > 0;
            
            if ($hasSemelhantes) {
                $stmt = $pdo->query('SELECT id, nome, descricao, imagem, link_steam, jogo_semelhante_1, jogo_semelhante_2, created_at FROM jogos ORDER BY created_at DESC');
            } else {
                $stmt = $pdo->query('SELECT id, nome, descricao, imagem, link_steam, created_at FROM jogos ORDER BY created_at DESC');
            }
            
            $jogos = $stmt->fetchAll();
            
            // Garantir que IDs são inteiros
            $jogos = array_map(function($jogo) use ($hasSemelhantes) {
                $jogo['id'] = (int)$jogo['id'];
                if ($hasSemelhantes) {
                    $jogo['jogo_semelhante_1'] = $jogo['jogo_semelhante_1'] ? (int)$jogo['jogo_semelhante_1'] : null;
                    $jogo['jogo_semelhante_2'] = $jogo['jogo_semelhante_2'] ? (int)$jogo['jogo_semelhante_2'] : null;
                }
                return $jogo;
            }, $jogos);
            
            echo json_encode($jogos);
            break;
            
        case 'POST':
            // Adicionar novo jogo
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['nome']) || empty($data['descricao']) || empty($data['imagem'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Dados incompletos']);
                exit;
            }
            
            // Verificar se as colunas de jogos semelhantes existem
            $columns = $pdo->query("SHOW COLUMNS FROM jogos LIKE 'jogo_semelhante_1'")->fetchAll();
            $hasSemelhantes = count($columns) > 0;
            
            $linkSteam = isset($data['link_steam']) ? $data['link_steam'] : null;
            
            if ($hasSemelhantes) {
                $semelhante1 = isset($data['jogo_semelhante_1']) ? $data['jogo_semelhante_1'] : null;
                $semelhante2 = isset($data['jogo_semelhante_2']) ? $data['jogo_semelhante_2'] : null;
                
                $stmt = $pdo->prepare('INSERT INTO jogos (nome, descricao, imagem, link_steam, jogo_semelhante_1, jogo_semelhante_2) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$data['nome'], $data['descricao'], $data['imagem'], $linkSteam, $semelhante1, $semelhante2]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO jogos (nome, descricao, imagem, link_steam) VALUES (?, ?, ?, ?)');
                $stmt->execute([$data['nome'], $data['descricao'], $data['imagem'], $linkSteam]);
            }
            
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;
            
        case 'PUT':
            // Atualizar jogo existente
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['id']) || empty($data['descricao'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Dados incompletos']);
                exit;
            }
            
            $linkSteam = isset($data['link_steam']) ? $data['link_steam'] : null;
            
            if (!empty($data['imagem'])) {
                $stmt = $pdo->prepare('UPDATE jogos SET descricao = ?, imagem = ?, link_steam = ? WHERE id = ?');
                $stmt->execute([$data['descricao'], $data['imagem'], $linkSteam, $data['id']]);
            } else {
                $stmt = $pdo->prepare('UPDATE jogos SET descricao = ?, link_steam = ? WHERE id = ?');
                $stmt->execute([$data['descricao'], $linkSteam, $data['id']]);
            }
            
            echo json_encode(['success' => true]);
            break;
            
        case 'DELETE':
            // Deletar jogo
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID não fornecido']);
                exit;
            }
            
            $stmt = $pdo->prepare('DELETE FROM jogos WHERE id = ?');
            $stmt->execute([$data['id']]);
            
            echo json_encode(['success' => true]);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro no servidor: ' . $e->getMessage()]);
}
