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
            // Buscar configuração atual do carrossel
            $stmt = $pdo->query('SELECT * FROM carrossel_config LIMIT 1');
            $config = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$config) {
                echo json_encode([
                    'jogo_1' => null,
                    'jogo_2' => null,
                    'jogo_3' => null,
                    'jogo_4' => null
                ]);
            } else {
                echo json_encode($config);
            }
            break;
            
        case 'POST':
            // Salvar configuração do carrossel
            $data = json_decode(file_get_contents('php://input'), true);
            
            $jogo1 = isset($data['jogo_1']) ? $data['jogo_1'] : null;
            $jogo2 = isset($data['jogo_2']) ? $data['jogo_2'] : null;
            $jogo3 = isset($data['jogo_3']) ? $data['jogo_3'] : null;
            $jogo4 = isset($data['jogo_4']) ? $data['jogo_4'] : null;
            
            // Verificar se já existe configuração
            $stmt = $pdo->query('SELECT id FROM carrossel_config LIMIT 1');
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                // Atualizar
                $stmt = $pdo->prepare('UPDATE carrossel_config SET jogo_1 = ?, jogo_2 = ?, jogo_3 = ?, jogo_4 = ? WHERE id = ?');
                $stmt->execute([$jogo1, $jogo2, $jogo3, $jogo4, $existing['id']]);
            } else {
                // Inserir
                $stmt = $pdo->prepare('INSERT INTO carrossel_config (jogo_1, jogo_2, jogo_3, jogo_4) VALUES (?, ?, ?, ?)');
                $stmt->execute([$jogo1, $jogo2, $jogo3, $jogo4]);
            }
            
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
