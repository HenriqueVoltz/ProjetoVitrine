<?php
require_once '../config/database.php';

$pdo = getConnection();
$stmt = $pdo->query("SELECT id, nome, imagem FROM jogos ORDER BY created_at DESC");
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Biblioteca de Jogos</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index_dinamico.php">Biblioteca</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index_dinamico.php">Biblioteca</a></li>
                    <li class="nav-item"><a class="nav-link" href="../Carrossel/index.php">Home</a></li>
                    <?php include __DIR__ . '/../auth_nav.php'; ?>
                </ul>
            </div>
        </div>
    </nav>

    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Biblioteca</h1>
                <p class="lead fw-normal text-white-50 mb-0">Todos os nossos jogos</p>
            </div>
        </div>
    </header>

    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <?php if (count($jogos) === 0): ?>
                <div class="alert alert-info text-center">
                    <h5>Nenhum jogo cadastrado ainda</h5>
                    <p>Acesse o painel admin para adicionar jogos</p>
                </div>
            <?php else: ?>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <?php foreach ($jogos as $jogo): ?>
                        <div class="col mb-5">
                            <div class="card h-100">
                                <img class="card-img-top" 
                                     src="<?php echo htmlspecialchars($jogo['imagem']); ?>" 
                                     alt="<?php echo htmlspecialchars($jogo['nome']); ?>"
                                     style="height: 300px; object-fit: cover;" />
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <h5 class="fw-bolder"><?php echo htmlspecialchars($jogo['nome']); ?></h5>
                                    </div>
                                </div>
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                    <div class="text-center">
                                        <a class="btn btn-outline-dark mt-auto" 
                                           href="../Descrição Game/jogo.php?id=<?php echo $jogo['id']; ?>">
                                            Ver Detalhes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Projeto Vitrine 2025</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
