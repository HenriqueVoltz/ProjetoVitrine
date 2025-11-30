<?php
require_once '../config/database.php';

$pdo = getConnection();
$stmt = $pdo->query("SELECT id, nome, descricao, imagem FROM jogos ORDER BY created_at DESC");
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop Item - Start Bootstrap Template</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="/ProjetoVitrine-main/Materiais/Descrição Game/index.php">Jogos</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="/ProjetoVitrine-main/Materiais/Carrossel/index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/ProjetoVitrine-main/Materiais/Biblioteca/index.php">Biblioteca</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Product section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="row gx-4 gx-lg-5 align-items-center">
                    <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="assets/favicon.ico" alt="Logo Projeto Vitrine" /></div>
                    <div class="col-md-6">
                        <h1 class="display-5 fw-bolder">Bem-vindo à Descrição dos Jogos</h1>
                        <p class="lead">Aqui você encontra detalhes, imagens e links para os principais jogos do nosso acervo. Clique em Biblioteca para explorar todos os títulos disponíveis.</p>
                        <div class="d-flex">
                            <a class="btn btn-outline-dark flex-shrink-0" href="https://store.steampowered.com/" target="_blank">
                                <i class="bi-cart-fill me-1"></i>
                                Ver na Steam
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Related items section-->
        <section class="py-5 bg-light">
            <div class="container px-4 px-lg-5 mt-5">
                <h2 class="fw-bolder mb-4">Jogos Disponíveis</h2>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <?php if (count($jogos) === 0): ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <p>Nenhum jogo cadastrado ainda. Acesse o painel admin para adicionar jogos.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($jogos as $jogo): ?>
                            <div class="col mb-5">
                                <div class="card h-100">
                                    <img class="card-img-top" 
                                         src="<?php echo htmlspecialchars($jogo['imagem']); ?>" 
                                         alt="<?php echo htmlspecialchars($jogo['nome']); ?>"
                                         style="height: 200px; object-fit: cover;" />
                                    <div class="card-body p-4">
                                        <div class="text-center">
                                            <h5 class="fw-bolder"><?php echo htmlspecialchars($jogo['nome']); ?></h5>
                                            <p class="text-muted small mt-2">
                                                <?php 
                                                $desc = htmlspecialchars($jogo['descricao']);
                                                echo strlen($desc) > 100 ? substr($desc, 0, 100) . '...' : $desc;
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                        <div class="text-center">
                                            <a class="btn btn-outline-dark mt-auto" 
                                               href="jogo.php?id=<?php echo $jogo['id']; ?>">
                                                Ver Mais
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="footer mt-auto py-4 bg-dark text-white">
            <div class="container text-center">
                <div class="row">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h6 class="mb-1">Projeto Vitrine</h6>
                        <small>&copy; 2025 P.E Corp</small>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <a href="/ProjetoVitrine-main/Materiais/Carrossel/index.php" class="text-white me-3">Home</a>
                        <a href="/ProjetoVitrine-main/Materiais/Biblioteca/index.php" class="text-white me-3">Biblioteca</a>
                        <?php include __DIR__ . '/../auth_footer.php'; ?>
                    </div>
                    <div class="col-md-4">
                        <span>Contato: <a href="mailto:henriquevoltz01@gmail.com" class="text-white">henriquevoltz01@gmail.com</a></span>
                    </div>
                </div>
            </div>
        </footer>
       <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
