<?php
session_start();
$_SESSION['is_admin'] = true; // Simular admin

require_once __DIR__ . '/Materiais/config/database.php';

header('Content-Type: application/json');

$pdo = getConnection();
if (!$pdo) {
    echo json_encode(['error' => 'Conexão falhou']);
    exit;
}

// Listar jogos
$stmt = $pdo->query('SELECT id, nome FROM jogos');
$jogos = $stmt->fetchAll();

echo json_encode([
    'jogos' => $jogos,
    'tipos' => array_map(function($j) {
        return [
            'id' => $j['id'],
            'tipo_id' => gettype($j['id']),
            'nome' => $j['nome']
        ];
    }, $jogos)
]);
