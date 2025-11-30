<?php
require_once '../config/database.php';
$pdo = getConnection();

$stmt = $pdo->prepare("SELECT * FROM jogos WHERE nome LIKE ?");
$stmt->execute(['%Kingdom Hearts 1.5%']);
$jogo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$jogo) {
    $jogo = [
        'nome' => 'Kingdom Hearts 1.5 + 2.5 ReMIX',
        'descricao' => 'Reviva as aventuras clássicas de Sora, Donald e Pateta em Kingdom Hearts 1.5 + 2.5 ReMIX, uma coletânea remasterizada dos primeiros jogos da série.',
        'imagem' => '../Biblioteca/ImagensBiblioteca/Kh1.5.png'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Kingdom Hearts 1.5</title>
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
                <a class="navbar-brand" href="/ProjetoVitrine-main/Materiais/Biblioteca/index.php" style="margin-left: 20px;">Biblioteca</a>
            </div>
        </nav>
        <!-- Product section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="row gx-4 gx-lg-5 align-items-center">
                    <div class="col-md-6">
                        <img class="card-img-top mb-5 mb-md-0" 
                             src="<?php echo htmlspecialchars($jogo['imagem']); ?>" 
                             alt="<?php echo htmlspecialchars($jogo['nome']); ?>" />
                    </div>
                    <div class="col-md-6">
                        <h1 class="display-5 fw-bolder"><?php echo htmlspecialchars($jogo['nome']); ?></h1>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($jogo['descricao'])); ?></p>
                        
                        <div class="d-flex">
                            <a class="btn btn-outline-dark flex-shrink-0" href="https://store.steampowered.com/app/2552430/KINGDOM_HEARTS_HD_1525_ReMIX/?curator_clanid=45029091" target="_blank">
                                <i class="bi-cart-fill me-1"></i>
                                Ver na Steam
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Seção de Itens Semelhantes -->
        <section class="py-5 bg-light">
            <div class="container px-4 px-lg-5 mt-5">
                <h3 class="mb-4">Itens semelhantes</h3>
                <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-2 row-cols-xl-2 justify-content-center">
                    <div class="col mb-5">
                        <div class="card h-100">
                            <img class="card-img-top" src="../Biblioteca/ImagensBiblioteca/KhFcPSlider.jpg" alt="Kingdom Hearts Final Chapter Prologue" height="180"/>
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h5 class="fw-bolder">KH Final Chapter Prologue</h5>
                                </div>
                            </div>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="/ProjetoVitrine-main/Materiais/Descrição Game/khfcp.php">Ver Mais</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <img class="card-img-top" src="../Carrossel/ImagensBiblioteca/Kh3Slider2.0.jpg" alt="Kingdom Hearts 3" height="180"/>
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h5 class="fw-bolder">Kingdom Hearts 3</h5>
                                </div>
                            </div>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="/ProjetoVitrine-main/Materiais/Descrição Game/kh3.php">Ver Mais</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
                <div id="footer-container"></div>
                <script>
                fetch('../footer.php')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('footer-container').innerHTML = html;
                    });
                </script>
</html>
