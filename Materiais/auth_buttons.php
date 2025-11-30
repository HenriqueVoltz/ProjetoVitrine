<?php
if (session_status() === PHP_SESSION_NONE) @session_start();
$username = $_SESSION['username'] ?? null;
$is_admin = $_SESSION['is_admin'] ?? false;
@session_write_close();

if (!empty($username)) {
    if (!empty($is_admin)) {
        echo '<span class="badge bg-danger me-2">Admin</span>';
        echo '<span style="color:#212529;margin-right:10px;">' . htmlspecialchars($username) . '</span>';
        echo '<a class="btn me-2" href="/ProjetoVitrine-main/Materiais/admin.php" style="border:none;color:#212529;background:none;">Painel Admin</a>';
    } else {
        echo '<span style="color:#212529;margin-right:10px;">' . htmlspecialchars($username) . '</span>';
    }
    echo '<a class="btn" href="/ProjetoVitrine-main/Materiais/logout.php" style="border:none;color:#212529;background:none;">Sair</a>';
} else {
    echo '<a class="btn me-2" href="/ProjetoVitrine-main/Materiais/Login.php" style="border:none;color:#212529;background:none;">Login</a>';
    echo '<a class="btn" href="/ProjetoVitrine-main/Materiais/Registro.php" style="border:none;color:#212529;background:none;">Cadastrar</a>';
}
?>
