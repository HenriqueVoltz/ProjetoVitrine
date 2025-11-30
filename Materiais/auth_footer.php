<?php
if (session_status() === PHP_SESSION_NONE) @session_start();
$username = $_SESSION['username'] ?? null;
$is_admin = $_SESSION['is_admin'] ?? false;
@session_write_close();

if (!empty($username)) {
    if (!empty($is_admin)) {
        echo '<span class="badge bg-danger me-2">Admin</span>';
        echo '<span class="text-white me-3">' . htmlspecialchars($username) . '</span>';
        echo '<a href="/ProjetoVitrine-main/Materiais/admin.php" class="text-white me-3">Painel Admin</a>';
    } else {
        echo '<span class="text-white me-3">' . htmlspecialchars($username) . '</span>';
    }
    echo '<a href="/ProjetoVitrine-main/Materiais/logout.php" class="text-white me-3">Sair</a>';
} else {
    echo '<a href="/ProjetoVitrine-main/Materiais/Login.php" class="text-white me-3">Entrar</a>';
    echo '<a href="/ProjetoVitrine-main/Materiais/Registro.php" class="text-white">Criar Conta</a>';
}
?>
