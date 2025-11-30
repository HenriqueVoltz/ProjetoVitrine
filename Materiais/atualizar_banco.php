<?php
require_once 'config/database.php';

try {
    $pdo = getConnection();
    
    // Verificar se as colunas já existem
    $stmt = $pdo->query("SHOW COLUMNS FROM jogos LIKE 'jogo_semelhante_1'");
    $hasSemelhantes = $stmt->rowCount() > 0;
    
    $stmt = $pdo->query("SHOW COLUMNS FROM jogos LIKE 'link_steam'");
    $hasLinkSteam = $stmt->rowCount() > 0;
    
    // Verificar se a tabela carrossel_config existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'carrossel_config'");
    $hasCarrosselTable = $stmt->rowCount() > 0;
    
    if ($hasSemelhantes && $hasLinkSteam && $hasCarrosselTable) {
        echo "Todas as colunas e tabelas já existem no banco de dados.";
        exit;
    }
    
    // Adicionar as colunas de jogos semelhantes se não existirem
    if (!$hasSemelhantes) {
        $pdo->exec("ALTER TABLE jogos 
                    ADD COLUMN jogo_semelhante_1 INT NULL,
                    ADD COLUMN jogo_semelhante_2 INT NULL");
        
        $pdo->exec("ALTER TABLE jogos 
                    ADD FOREIGN KEY (jogo_semelhante_1) REFERENCES jogos(id) ON DELETE SET NULL,
                    ADD FOREIGN KEY (jogo_semelhante_2) REFERENCES jogos(id) ON DELETE SET NULL");
        echo "Colunas de jogos semelhantes adicionadas.<br>";
    }
    
    // Adicionar a coluna link_steam se não existir
    if (!$hasLinkSteam) {
        $pdo->exec("ALTER TABLE jogos ADD COLUMN link_steam VARCHAR(500) NULL");
        echo "Coluna link_steam adicionada.<br>";
    }
    
    // Criar tabela carrossel_config se não existir
    if (!$hasCarrosselTable) {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS carrossel_config (
                id INT AUTO_INCREMENT PRIMARY KEY,
                jogo_1 INT NULL,
                jogo_2 INT NULL,
                jogo_3 INT NULL,
                jogo_4 INT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (jogo_1) REFERENCES jogos(id) ON DELETE SET NULL,
                FOREIGN KEY (jogo_2) REFERENCES jogos(id) ON DELETE SET NULL,
                FOREIGN KEY (jogo_3) REFERENCES jogos(id) ON DELETE SET NULL,
                FOREIGN KEY (jogo_4) REFERENCES jogos(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "Tabela carrossel_config criada.<br>";
    }
    
    echo "<br>Banco de dados atualizado com sucesso!";
} catch (Exception $e) {
    echo "Erro ao atualizar banco de dados: " . $e->getMessage();
}
?>
