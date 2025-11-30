<?php
if (session_status() === PHP_SESSION_NONE) @session_start();
$username = $_SESSION['username'] ?? null;
$is_admin = $_SESSION['is_admin'] ?? false;
@session_write_close();

if (!empty($username)) {
    if (!empty($is_admin)) {
        echo '<li class="nav-item"><span class="nav-link"><span class="badge bg-danger">Admin</span> ' . htmlspecialchars($username) . '</span></li>';
        echo '<li class="nav-item"><a class="nav-link" href="/ProjetoVitrine-main/Materiais/admin.php">Painel Admin</a></li>';
    } else {
        echo '<li class="nav-item"><span class="nav-link">' . htmlspecialchars($username) . '</span></li>';
    }
    echo '<li class="nav-item"><a class="nav-link" href="/ProjetoVitrine-main/Materiais/logout.php">Sair</a></li>';
} else {
    echo '<li class="nav-item"><a class="nav-link" href="/ProjetoVitrine-main/Materiais/Login.php">Login</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="/ProjetoVitrine-main/Materiais/Registro.php">Cadastrar</a></li>';
}
?>
