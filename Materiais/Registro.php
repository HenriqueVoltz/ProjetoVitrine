<?php
session_start();
require_once __DIR__ . '/config/database.php';

$register_error = '';
$register_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $senha = $_POST['senha'] ?? '';
  $senha2 = $_POST['senha2'] ?? '';
  $termos = isset($_POST['termos']);

  if ($nome === '' || $email === '' || $senha === '' || $senha2 === '') {
    $register_error = 'Preencha todos os campos.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $register_error = 'Email inválido.';
  } elseif (strlen($senha) < 6) {
    $register_error = 'A senha deve ter no mínimo 6 caracteres.';
  } elseif ($senha !== $senha2) {
    $register_error = 'Senhas não conferem.';
  } elseif (!$termos) {
    $register_error = 'É necessário aceitar os termos.';
  } else {
    try {
      $pdo = getConnection();
      if (!$pdo) {
        $register_error = 'Erro ao conectar com o banco de dados.';
      } else {
        // Verificar se username já existe
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$nome]);
        if ($stmt->fetch()) {
          $register_error = 'Nome de usuário já está em uso.';
        } else {
          // Verificar se email já existe
          $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
          $stmt->execute([$email]);
          if ($stmt->fetch()) {
            $register_error = 'Email já está cadastrado.';
          } else {
            // Criar novo usuário
            $password_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
            $stmt->execute([$nome, $email, $password_hash, 'user']);
            $register_success = true;
          }
        }
      }
    } catch (Exception $e) {
      error_log('Registration error: ' . $e->getMessage());
      $register_error = 'Erro ao processar cadastro. Tente novamente.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/auth-admin.css">
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Cadastro</h2>
    <?php if ($register_success): ?>
      <div class="alert alert-success">
        Cadastro realizado com sucesso! <a href="Login.php">Clique aqui para fazer login</a>
      </div>
    <?php elseif ($register_error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($register_error) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome" value="<?= htmlspecialchars($nome ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" value="<?= htmlspecialchars($email ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
      </div>
      <div class="mb-3">
        <label for="senha2" class="form-label">Repita a senha</label>
        <input type="password" class="form-control" id="senha2" name="senha2" placeholder="Repita sua senha" required>
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="termos" name="termos" required>
        <label class="form-check-label" for="termos">Aceito os termos de uso</label>
      </div>
      <button type="submit" class="btn btn-success w-100">Cadastrar</button>
      <div class="text-center mt-3">
        <a href="Login.php" class="small">Já tem conta? Faça login</a>
      </div>
      <div class="text-center mt-3">
        <a href="Carrossel/index.php" class="btn btn-secondary">Voltar ao Início</a>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

