<?php
require_once 'config/database.php';

$pdo = getConnection();

echo "<h2>Jogos no Banco de Dados:</h2>";
$stmt = $pdo->query("SELECT id, nome, descricao, updated_at FROM jogos ORDER BY id");
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($jogos) === 0) {
    echo "<p>Nenhum jogo encontrado</p>";
} else {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Descrição</th><th>Última Atualização</th></tr>";
    foreach ($jogos as $jogo) {
        echo "<tr>";
        echo "<td>{$jogo['id']}</td>";
        echo "<td>{$jogo['nome']}</td>";
        echo "<td>" . substr($jogo['descricao'], 0, 50) . "...</td>";
        echo "<td>{$jogo['updated_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<br><br>";
echo "<a href='admin.php'>← Voltar ao Admin</a> | ";
echo "<a href='Descrição Game/index.php'>Ver Página de Jogos</a>";
?>
