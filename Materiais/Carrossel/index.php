<?php
require_once '../config/database.php';
$pdo = getConnection();

// Buscar configuração do carrossel
$stmt = $pdo->query("SELECT * FROM carrossel_config LIMIT 1");
$carrosselConfig = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar jogos para o carrossel
$jogosCarrossel = [];
if ($carrosselConfig) {
    for ($i = 1; $i <= 4; $i++) {
        if ($carrosselConfig["jogo_$i"]) {
            $stmt = $pdo->prepare("SELECT * FROM jogos WHERE id = ?");
            $stmt->execute([$carrosselConfig["jogo_$i"]]);
            $jogo = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($jogo) $jogosCarrossel[] = $jogo;
        }
    }
}

// Se não houver configuração, usar os 3 mais recentes
if (empty($jogosCarrossel)) {
    $stmt = $pdo->query("SELECT * FROM jogos ORDER BY created_at DESC LIMIT 3");
    $jogosCarrossel = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar atualizações recentes (não jogos, mas atualizações individuais)
$stmt = $pdo->query("
    SELECT a.id as atualizacao_id, a.data_atualizacao, a.nota, j.id as jogo_id, j.nome, j.imagem 
    FROM atualizacoes a
    INNER JOIN jogos j ON a.jogo_id = j.id 
    ORDER BY a.data_atualizacao DESC 
    LIMIT 8
");
$atualizacoesRecentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Business Frontpage - Start Bootstrap Template</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="https://unpkg.com/swiper@8/swiper-bundle.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Responsive navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-5">
                <a class="navbar-brand" href="/ProjetoVitrine-main/Materiais/Carrossel/index.php">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="/ProjetoVitrine-main/Materiais/Carrossel/index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/ProjetoVitrine-main/Materiais/Biblioteca/index.php">Biblioteca</a></li>
                        <?php include __DIR__ . '/../auth_nav.php'; ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Header-->  
        <header class="bg-dark py-5">
            <div class="container">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($jogosCarrossel as $jogo): ?>
                        <div class="swiper-slide">
                            <div class="project-img">
                                <img src="<?php echo htmlspecialchars($jogo['imagem']); ?>" 
                                     alt="<?php echo htmlspecialchars($jogo['nome']); ?>" 
                                     style="width:95%;height:300px;object-fit:cover;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.2);">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </header>
        <!-- Quadrados abaixo do carrossel -->
        
        </footer>
        <section class="py-3 bg-light">
            <div class="text-center mb-2" style="font-size: 1.25rem; font-weight: 600; color: #222;">Atualizações</div>
            <div class="container d-flex flex-column align-items-center img-group-border">
                <div class="row justify-content-center gap-4 flex-wrap w-100" style="max-width: 700px;">
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <?php 
                        $count = 0;
                        foreach ($atualizacoesRecentes as $atualizacao): 
                            $count++;
                            if ($count > 4) break; // Primeira linha - 4 atualizações
                        ?>
                        <a href="../Zona de Update/atualizacao.php?id=<?php echo $atualizacao['atualizacao_id']; ?>" 
                           class="card" 
                           style="width: 140px; min-width: 120px; cursor: pointer; transition: transform 0.2s; text-decoration: none;">
                            <img class="card-img-top" 
                                 src="<?php echo htmlspecialchars($atualizacao['imagem']); ?>" 
                                 alt="<?php echo htmlspecialchars($atualizacao['nome']); ?>" 
                                 style="height: 100px; object-fit: cover; width:100%;">
                            <div class="card-body p-2" style="background: #f8f9fa;">
                                <small class="text-muted d-block text-center" style="font-size: 0.7rem;">
                                    <?php echo date('d/m/Y', strtotime($atualizacao['data_atualizacao'])); ?>
                                </small>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="d-flex justify-content-center gap-4 flex-wrap mt-3">
                        <?php 
                        $count = 0;
                        foreach ($atualizacoesRecentes as $atualizacao): 
                            $count++;
                            if ($count <= 4) continue; // Pula as primeiras 4
                            if ($count > 8) break; // Segunda linha - próximas 4 atualizações
                        ?>
                        <a href="../Zona de Update/atualizacao.php?id=<?php echo $atualizacao['atualizacao_id']; ?>" 
                           class="card" 
                           style="width: 140px; min-width: 120px; cursor: pointer; transition: transform 0.2s; text-decoration: none;">
                            <img class="card-img-top" 
                                 src="<?php echo htmlspecialchars($atualizacao['imagem']); ?>" 
                                 alt="<?php echo htmlspecialchars($atualizacao['nome']); ?>" 
                                 style="height: 100px; object-fit: cover; width:100%;">
                            <div class="card-body p-2" style="background: #f8f9fa;">
                                <small class="text-muted d-block text-center" style="font-size: 0.7rem;">
                                    <?php echo date('d/m/Y', strtotime($atualizacao['data_atualizacao'])); ?>
                                </small>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>  
        <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
        <div id="footer-container"></div>
        <script>
        fetch('../footer.php')
            .then(response => response.text())
            .then(html => {
                document.getElementById('footer-container').innerHTML = html;
            });
        </script>
