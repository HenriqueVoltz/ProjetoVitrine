<?php
require_once '../config/database.php';

$jogo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($jogo_id <= 0) {
    header('Location: index.php');
    exit;
}

$pdo = getConnection();
$stmt = $pdo->prepare("SELECT * FROM jogos WHERE id = ?");
$stmt->execute([$jogo_id]);
$jogo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$jogo) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="<?php echo htmlspecialchars($jogo['descricao']); ?>" />
    <title><?php echo htmlspecialchars($jogo['nome']); ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        section {
            flex: 1;
        }
        #footer-container {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="../Carrossel/index.php">Home</a>
            <a class="navbar-brand" href="../Biblioteca/index.php" style="margin-left: 20px;">Biblioteca</a>
            <div class="d-flex ms-auto">
                <?php include __DIR__ . '/../auth_buttons.php'; ?>
            </div>
        </div>
    </nav>
    
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6">
                    <img class="card-img-top mb-5 mb-md-0" 
                         src="<?php echo htmlspecialchars($jogo['imagem']); ?>" 
                         alt="<?php echo htmlspecialchars($jogo['nome']); ?>" 
                         style="max-width: 100%; height: auto;" />
                </div>
                <div class="col-md-6">
                    <h1 class="display-5 fw-bolder"><?php echo htmlspecialchars($jogo['nome']); ?></h1>
                    <p class="lead"><?php echo nl2br(htmlspecialchars($jogo['descricao'])); ?></p>
                    
                    <?php if (!empty($jogo['link_steam'])): ?>
                    <div class="d-flex mb-3">
                        <a class="btn btn-outline-dark flex-shrink-0" href="<?php echo htmlspecialchars($jogo['link_steam']); ?>" target="_blank">
                            <i class="bi-cart-fill me-1"></i>
                            Ver na Steam
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <?php
    // Verificar se as colunas de jogos semelhantes existem
    $columns = $pdo->query("SHOW COLUMNS FROM jogos LIKE 'jogo_semelhante_1'")->fetchAll();
    $hasSemelhantes = count($columns) > 0;
    
    // Buscar jogos semelhantes se existirem
    $jogos_semelhantes = [];
    if ($hasSemelhantes) {
        if (isset($jogo['jogo_semelhante_1']) && $jogo['jogo_semelhante_1']) {
            $stmt = $pdo->prepare("SELECT id, nome, imagem FROM jogos WHERE id = ?");
            $stmt->execute([$jogo['jogo_semelhante_1']]);
            $semelhante = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($semelhante) $jogos_semelhantes[] = $semelhante;
        }
        if (isset($jogo['jogo_semelhante_2']) && $jogo['jogo_semelhante_2']) {
            $stmt = $pdo->prepare("SELECT id, nome, imagem FROM jogos WHERE id = ?");
            $stmt->execute([$jogo['jogo_semelhante_2']]);
            $semelhante = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($semelhante) $jogos_semelhantes[] = $semelhante;
        }
    }
    
    if (count($jogos_semelhantes) > 0): ?>
    <section class="py-5 bg-light">
        <div class="container px-4 px-lg-5 mt-5">
            <h3 class="mb-4">Itens semelhantes</h3>
            <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-2 row-cols-xl-<?php echo count($jogos_semelhantes); ?> justify-content-center">
                <?php foreach ($jogos_semelhantes as $semelhante): ?>
                <div class="col mb-5">
                    <div class="card h-100">
                        <img class="card-img-top" 
                             src="<?php echo htmlspecialchars($semelhante['imagem']); ?>" 
                             alt="<?php echo htmlspecialchars($semelhante['nome']); ?>" 
                             height="180"
                             style="object-fit: cover;"/>
                        <div class="card-body p-4">
                            <div class="text-center">
                                <h5 class="fw-bolder"><?php echo htmlspecialchars($semelhante['nome']); ?></h5>
                            </div>
                        </div>
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <a class="btn btn-outline-dark mt-auto" href="jogo.php?id=<?php echo $semelhante['id']; ?>">Ver Mais</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <div id="footer-container"></div>
    <script>
    fetch('../footer.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('footer-container').innerHTML = html;
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
