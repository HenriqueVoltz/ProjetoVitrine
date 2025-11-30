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
            // Listar todas as atualizações com informações do jogo
            $stmt = $pdo->query('
                SELECT a.id, a.jogo_id, a.nota, a.data_atualizacao, a.created_at, 
                       j.nome as jogo_nome, j.imagem as jogo_imagem
                FROM atualizacoes a
                JOIN jogos j ON a.jogo_id = j.id
                ORDER BY a.data_atualizacao DESC, a.created_at DESC
            ');
            $atualizacoes = $stmt->fetchAll();
            echo json_encode($atualizacoes);
            break;
            
        case 'POST':
            // Adicionar nova atualização
            $data = json_decode(file_get_contents('php://input'), true);
            
            error_log('Dados recebidos para atualização: ' . print_r($data, true));
            
            // Validação mais específica
            if (!isset($data['jogo_id']) || $data['jogo_id'] === '' || $data['jogo_id'] === null) {
                http_response_code(400);
                echo json_encode([
                    'error' => 'jogo_id não fornecido',
                    'received' => $data
                ]);
                exit;
            }
            
            if (!isset($data['nota']) || trim($data['nota']) === '') {
                http_response_code(400);
                echo json_encode([
                    'error' => 'nota não fornecida',
                    'received' => $data
                ]);
                exit;
            }
            
            if (!isset($data['data_atualizacao']) || $data['data_atualizacao'] === '') {
                http_response_code(400);
                echo json_encode([
                    'error' => 'data_atualizacao não fornecida',
                    'received' => $data
                ]);
                exit;
            }
            
            // Converter jogo_id para inteiro
            $jogoId = intval($data['jogo_id']);
            
            // Verificar se o jogo existe
            $checkStmt = $pdo->prepare('SELECT id FROM jogos WHERE id = ?');
            $checkStmt->execute([$jogoId]);
            if (!$checkStmt->fetch()) {
                http_response_code(400);
                echo json_encode([
                    'error' => 'Jogo não encontrado',
                    'jogo_id' => $jogoId
                ]);
                exit;
            }
            
            $stmt = $pdo->prepare('INSERT INTO atualizacoes (jogo_id, nota, data_atualizacao) VALUES (?, ?, ?)');
            $result = $stmt->execute([$jogoId, $data['nota'], $data['data_atualizacao']]);
            
            if (!$result) {
                http_response_code(500);
                echo json_encode(['error' => 'Erro ao inserir no banco', 'info' => $stmt->errorInfo()]);
                exit;
            }
            
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;
            
        case 'DELETE':
            // Deletar atualização
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID não fornecido']);
                exit;
            }
            
            $stmt = $pdo->prepare('DELETE FROM atualizacoes WHERE id = ?');
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
