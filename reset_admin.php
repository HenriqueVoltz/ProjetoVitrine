<?php
// Script para resetar senha do admin via navegador
require_once __DIR__ . '/Materiais/config/database.php';

$senha = '123456';
$hash = password_hash($senha, PASSWORD_DEFAULT);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Reset Admin</title></head><body>";
echo "<h2>Resetando senha do admin...</h2>";

try {
    $pdo = getConnection();
    if (!$pdo) {
        echo "<p style='color:red;'>Erro ao conectar ao banco de dados!</p>";
    } else {
        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE username = ?');
        $stmt->execute([$hash, 'P.E.corp']);
        
        echo "<p style='color:green;'><strong>✓ Senha atualizada com sucesso!</strong></p>";
        echo "<hr>";
        echo "<p><strong>Credenciais:</strong></p>";
        echo "<ul>";
        echo "<li>Usuário: <strong>P.E.corp</strong></li>";
        echo "<li>Senha: <strong>123456</strong></li>";
        echo "</ul>";
        echo "<hr>";
        echo "<p><a href='Materiais/Login.php' style='padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;'>Ir para Login</a></p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";
