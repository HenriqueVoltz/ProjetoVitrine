<?php
require_once '../config/database.php';
$pdo = getConnection();
$stmt = $pdo->query("SELECT * FROM jogos ORDER BY created_at DESC");
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mapear nomes para páginas
$paginas = [
    'Kingdom Hearts 1.5' => 'kh1_5.php',
    'Kingdom Hearts Final Chapter Prologue' => 'khfcp.php',
    'Kingdom Hearts 3' => 'kh3.php',
    'Monster Hunter World' => 'mhworld.php',
    'Monster Hunter Rise' => 'mhrise.php',
    'Monster Hunter Stories' => 'mhstories.php',
    'Monster Hunter Stories 2' => 'mhstories2.php',
    'Monster Hunter Wilds' => 'mhwilds.php'
];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop Homepage - Start Bootstrap Template</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <style>
            .hidden-game {
                display: none !important;
            }
        </style>
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="/ProjetoVitrine-main/Materiais/Biblioteca/index.php" id="biblioteca-btn">Biblioteca</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="/ProjetoVitrine-main/Materiais/Biblioteca/index.php" id="biblioteca-link">Biblioteca</a></li>
                        <li class="nav-item"><a class="nav-link" href="/ProjetoVitrine-main/Materiais/Carrossel/index.php">Home</a></li>
                        <?php include __DIR__ . '/../auth_nav.php'; ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Biblioteca</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Todos os nossos jogos</p>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="jogos-container">
                    <?php 
                    $count = 0;
                    foreach ($jogos as $jogo): 
                        $count++;
                        // Verificar se existe página específica para este jogo
                        $pagina = '../Descrição Game/jogo.php?id=' . $jogo['id']; // padrão para novos jogos
                        
                        foreach ($paginas as $nomeChave => $paginaArquivo) {
                            if (stripos($jogo['nome'], $nomeChave) !== false) {
                                $pagina = '../Descrição Game/' . $paginaArquivo;
                                break;
                            }
                        }
                        
                        $hiddenClass = $count > 8 ? ' hidden-game' : '';
                    ?>
                    <div class="col mb-5<?php echo $hiddenClass; ?>" id="jogo-<?php echo $jogo['id']; ?>">
                        <div class="card h-100">
                            <img class="card-img-top" 
                                 src="<?php echo htmlspecialchars($jogo['imagem']); ?>" 
                                 alt="<?php echo htmlspecialchars($jogo['nome']); ?>" 
                                 width="450px" 
                                 height="300px"
                                 style="object-fit: cover;" />
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h5 class="fw-bolder"><?php echo htmlspecialchars($jogo['nome']); ?></h5>
                                </div>
                            </div>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center">
                                    <a class="btn btn-outline-dark mt-auto" href="<?php echo $pagina; ?>">Ver Mais</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($jogos) > 8): ?>
                <div class="text-center mt-4">
                    <button id="btn-mostrar-mais" class="btn btn-primary btn-lg">
                        Mostrar Mais
                    </button>
                    <button id="btn-mostrar-menos" class="btn btn-secondary btn-lg" style="display: none;">
                        Mostrar Menos
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <!-- Footer-->
                <div id="footer-container"></div>
                <script>
                fetch('../footer.php')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('footer-container').innerHTML = html;
                    });
                </script>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <script>
            document.getElementById('btn-mostrar-mais')?.addEventListener('click', function() {
                const hiddenGames = document.querySelectorAll('.hidden-game');
                hiddenGames.forEach(game => game.classList.remove('hidden-game'));
                this.style.display = 'none';
                document.getElementById('btn-mostrar-menos').style.display = 'inline-block';
            });
            
            document.getElementById('btn-mostrar-menos')?.addEventListener('click', function() {
                const allGames = document.querySelectorAll('#jogos-container .col');
                allGames.forEach((game, index) => {
                    if (index >= 8) {
                        game.classList.add('hidden-game');
                    }
                });
                this.style.display = 'none';
                document.getElementById('btn-mostrar-mais').style.display = 'inline-block';
                
                // Scroll suave para o topo da biblioteca
                document.querySelector('header').scrollIntoView({ behavior: 'smooth' });
            });
            
            // Verificar se há hash na URL e revelar jogo se necessário
            window.addEventListener('DOMContentLoaded', function() {
                if (window.location.hash) {
                    const targetId = window.location.hash.substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement && targetElement.classList.contains('hidden-game')) {
                        // Revelar todos os jogos se o jogo alvo estiver escondido
                        const hiddenGames = document.querySelectorAll('.hidden-game');
                        hiddenGames.forEach(game => game.classList.remove('hidden-game'));
                        
                        // Atualizar botões
                        const btnMostrarMais = document.getElementById('btn-mostrar-mais');
                        const btnMostrarMenos = document.getElementById('btn-mostrar-menos');
                        if (btnMostrarMais) btnMostrarMais.style.display = 'none';
                        if (btnMostrarMenos) btnMostrarMenos.style.display = 'inline-block';
                    }
                    
                    // Fazer scroll suave até o jogo
                    setTimeout(() => {
                        if (targetElement) {
                            targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }, 100);
                }
            });
        </script>
    </body>

</html>
