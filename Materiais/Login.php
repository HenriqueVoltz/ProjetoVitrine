<?php
session_start();
require_once __DIR__ . '/config/database.php';

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario = trim($_POST['usuario'] ?? '');
  $senha = $_POST['password'] ?? '';
  
  if ($usuario === '' || $senha === '') {
    $login_error = 'Preencha usuário e senha.';
  } else {
    try {
      $pdo = getConnection();
      if (!$pdo) {
        $login_error = 'Erro ao conectar com o banco de dados.';
      } else {
        $stmt = $pdo->prepare('SELECT id, username, email, password, role FROM users WHERE username = ?');
        $stmt->execute([$usuario]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($senha, $user['password'])) {
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['email'] = $user['email'];
          $_SESSION['is_admin'] = ($user['role'] === 'admin');
          header('Location: Carrossel/index.php');
          exit;
        } else {
          $login_error = 'Usuário ou senha incorretos.';
        }
      }
    } catch (Exception $e) {
      error_log('Login error: ' . $e->getMessage());
      $login_error = 'Erro ao processar login. Tente novamente.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/auth-admin.css">
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Login</h2>
    <?php if ($login_error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($login_error) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label for="usuario" class="form-label">Usuário</label>
        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Digite seu usuário" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Entrar</button>
      <div class="text-center mt-3">
        <a href="Esqueceu_Senha.php" class="small">Esqueceu a senha?</a>
      </div>
      <hr>
      <div class="text-center">
        <span>Não tem conta?</span> <a href="Registro.php">Cadastre-se</a>
      </div>
      <div class="text-center mt-3">
        <a href="Carrossel/index.php" class="btn btn-secondary">Voltar ao Início</a>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>