<!DOCTYPE HTML>
<?php
require_once '../config/database.php';
$pdo = getConnection();

// Pegar ID do jogo da URL
$jogo_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Buscar informações do jogo
$jogo = null;
$atualizacoes = [];

if ($jogo_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM jogos WHERE id = ?");
    $stmt->execute([$jogo_id]);
    $jogo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($jogo) {
        // Buscar atualizações do jogo
        $stmt_updates = $pdo->prepare("SELECT * FROM atualizacoes WHERE jogo_id = ? ORDER BY data_atualizacao DESC");
        $stmt_updates->execute([$jogo_id]);
        $atualizacoes = $stmt_updates->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!--
	Phantom by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title><?php echo $jogo ? htmlspecialchars($jogo['nome']) . ' - Atualizações' : 'Jogo não encontrado'; ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
		<style>
			/* Override para o footer ficar igual às outras páginas */
			html, body {
				height: 100%;
				margin: 0;
			}
			body {
				display: flex;
				flex-direction: column;
				min-height: 100vh;
			}
			#wrapper {
				flex: 1 0 auto;
				padding-bottom: 2rem;
			}
			#footer-container {
				flex-shrink: 0;
				margin-top: 0;
			}
			body #footer-container footer,
			body #footer-container footer.footer {
				background-color: #212529 !important;
				color: white !important;
				text-align: center !important;
				padding: 0.8rem 0 !important;
				margin-top: 0 !important;
			}
			body #footer-container footer .container {
				text-align: center !important;
			}
			body #footer-container footer .row {
				justify-content: center !important;
				align-items: center !important;
			}
			body #footer-container footer .col-md-4 {
				text-align: center !important;
			}
			body #footer-container footer *,
			body #footer-container footer h6,
			body #footer-container footer small,
			body #footer-container footer span {
				color: white !important;
				text-align: center !important;
			}
			body #footer-container footer a {
				color: white !important;
				text-decoration: none !important;
			}
			body #footer-container footer a:hover {
				text-decoration: underline !important;
			}
			
			/* Estilos para as atualizações */
			.update-item {
				background: rgba(255, 255, 255, 0.05);
				border-left: 4px solid #f56a6a;
				padding: 1.5rem;
				margin-bottom: 1.5rem;
				border-radius: 4px;
			}
			.update-item:hover {
				background: rgba(255, 255, 255, 0.08);
				transform: translateX(5px);
			}
			.update-date {
				color: #f56a6a;
				font-weight: bold;
				font-size: 1.1em;
				margin-bottom: 0.5rem;
			}
			.update-note {
				line-height: 1.8;
				color: #2c3e50;
				font-size: 1em;
			}
			.game-image {
				width: 100%;
				max-width: 700px;
				height: auto;
				max-height: 350px;
				object-fit: contain;
				display: block;
				margin: 0 auto 2rem auto;
				border-radius: 10px;
				box-shadow: 0 2px 10px rgba(0,0,0,0.2);
			}
			.no-updates {
				text-align: center;
				padding: 3rem;
				color: rgba(255, 255, 255, 0.5);
				font-style: italic;
			}
		</style>
	</head>
	<body class="is-preload">
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="inner">
							<a href="/ProjetoVitrine-main/Materiais/Carrossel/index.php" class="button small" style="float:right;margin-top:10px;">Home</a>
						</div>
					</header>

				<!-- Menu -->
					<nav id="menu">
						<h2>Menu</h2>
						<ul>
							<li><a href="/ProjetoVitrine-main/Materiais/Carrossel/index.php">Home</a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<?php if ($jogo): ?>
								<h1><?php echo htmlspecialchars($jogo['nome']); ?> - Atualizações</h1>
								
								<?php if ($jogo['imagem']): ?>
									<img src="<?php echo htmlspecialchars($jogo['imagem']); ?>" alt="<?php echo htmlspecialchars($jogo['nome']); ?>" class="game-image">
								<?php endif; ?>
								
								<?php if (count($atualizacoes) > 0): ?>
									<h2>Histórico de Atualizações</h2>
									<?php foreach ($atualizacoes as $update): ?>
										<a href="atualizacao.php?id=<?php echo $update['id']; ?>" style="text-decoration: none; color: inherit;">
											<div class="update-item" style="cursor: pointer; transition: transform 0.2s;">
												<div class="update-date">
													<?php 
													$date = new DateTime($update['data_atualizacao']);
													echo $date->format('d/m/Y');
													?>
												</div>
												<div class="update-note">
													<?php echo nl2br(htmlspecialchars($update['nota'])); ?>
												</div>
											</div>
										</a>
									<?php endforeach; ?>
								<?php else: ?>
									<div class="no-updates">
										Nenhuma atualização disponível para este jogo.
									</div>
								<?php endif; ?>
								
								<p style="margin-top: 2rem;">
									<a href="../Descrição Game/jogo.php?id=<?php echo $jogo_id; ?>" class="button">Voltar para o Jogo</a>
								</p>
							<?php else: ?>
								<h1>Jogo não encontrado</h1>
								<p>O jogo solicitado não foi encontrado no sistema.</p>
								<p>
									<a href="/ProjetoVitrine-main/Materiais/Carrossel/index.php" class="button">Voltar para Home</a>
								</p>
							<?php endif; ?>
						</div>
					</div>
			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

		<div id="footer-container"></div>
                <script>
                fetch('../footer.php')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('footer-container').innerHTML = html;
                    });
                </script>
	</body>
								
</html>