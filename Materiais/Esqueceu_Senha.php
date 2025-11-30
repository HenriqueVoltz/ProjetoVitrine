<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Senha</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/auth-admin.css">
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Recuperar Senha</h2>
    <div class="alert alert-info">Função temporariamente indisponível. Entre em contato com o administrador.</div>
    <form onsubmit="return false;">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" placeholder="Digite seu email" disabled>
      </div>
      <button type="submit" class="btn btn-primary w-100" disabled>Enviar instruções</button>
      <div class="text-center mt-3">
        <a href="Login.php" class="small">Voltar ao login</a>
      </div>
      <div class="text-center mt-2">
        <a href="Registro.php" class="small">Não tem conta? Cadastre-se</a>
      </div>
      <div class="text-center mt-3">
        <a href="Carrossel/index.php" class="btn btn-secondary">Voltar ao Início</a>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>