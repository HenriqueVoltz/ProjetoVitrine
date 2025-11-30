<?php
require_once 'config/database.php';

function imageToBase64($imagePath) {
    if (!file_exists($imagePath)) {
        return null;
    }
    $imageData = file_get_contents($imagePath);
    $base64 = base64_encode($imageData);
    $mimeType = mime_content_type($imagePath);
    return "data:$mimeType;base64,$base64";
}

$pdo = getConnection();

$jogos = [
    [
        'nome' => 'Kingdom Hearts 3',
        'descricao' => 'Acompanhe Sora e seus amigos em uma jornada épica para salvar mundos Disney e Pixar, com gráficos modernos e batalhas emocionantes.',
        'imagem' => 'Carrossel/ImagensBiblioteca/Kh3Slider2.0.jpg'
    ],
    [
        'nome' => 'Kingdom Hearts 1.5',
        'descricao' => 'Reviva a aventura clássica que iniciou a série Kingdom Hearts, agora remasterizada em alta definição.',
        'imagem' => 'Biblioteca/ImagensBiblioteca/Kh1.5Slider.png'
    ],
    [
        'nome' => 'Kingdom Hearts Final Chapter Prologue',
        'descricao' => 'O capítulo final da saga Dark Seeker traz conclusões épicas e prepara o terreno para o futuro da série.',
        'imagem' => 'Biblioteca/ImagensBiblioteca/KhFcPSlider.jpg'
    ],
    [
        'nome' => 'Monster Hunter World',
        'descricao' => 'Explore o Novo Mundo e caçe monstros colossais em vastos ecossistemas vivos. Uma experiência de caça épica e cooperativa.',
        'imagem' => 'Carrossel/ImagensBiblioteca/MhWorldSlider2.0.jfif'
    ],
    [
        'nome' => 'Monster Hunter Rise',
        'descricao' => 'Escale montanhas e use o Wirebug para caçar de formas nunca vistas antes. Uma nova era da caça começa.',
        'imagem' => 'Carrossel/ImagensBiblioteca/MhrSlider.jpg'
    ],
    [
        'nome' => 'Monster Hunter Stories',
        'descricao' => 'Uma aventura RPG única no universo Monster Hunter onde você pode criar laços com os monstros ao invés de caçá-los.',
        'imagem' => 'Biblioteca/ImagensBiblioteca/MhStoriesSlider.jpg'
    ],
    [
        'nome' => 'Monster Hunter Stories 2: Wings of Ruin',
        'descricao' => 'Continue a jornada como um Rider e descubra o mistério por trás do desaparecimento dos Rathalos.',
        'imagem' => 'Zona de Update/images/Stories2.jpg'
    ],
    [
        'nome' => 'Monster Hunter Wilds',
        'descricao' => 'A próxima geração de Monster Hunter está chegando. Prepare-se para explorar territórios selvagens inexplorados.',
        'imagem' => 'Carrossel/ImagensBiblioteca/MhWildsSlider2.0.jpg'
    ]
];

try {
    $stmt = $pdo->prepare("INSERT INTO jogos (nome, descricao, imagem) VALUES (?, ?, ?)");
    
    $sucessos = 0;
    $erros = 0;
    
    foreach ($jogos as $jogo) {
        $imagePath = __DIR__ . '/' . $jogo['imagem'];
        $imageBase64 = imageToBase64($imagePath);
        
        if ($imageBase64 === null) {
            echo "❌ Imagem não encontrada: {$jogo['imagem']}<br>";
            $erros++;
            continue;
        }
        
        try {
            $stmt->execute([
                $jogo['nome'],
                $jogo['descricao'],
                $imageBase64
            ]);
            echo "✅ {$jogo['nome']} adicionado com sucesso!<br>";
            $sucessos++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "⚠️ {$jogo['nome']} já existe no banco<br>";
            } else {
                echo "❌ Erro ao adicionar {$jogo['nome']}: " . $e->getMessage() . "<br>";
            }
            $erros++;
        }
    }
    
    echo "<br><strong>Resumo:</strong><br>";
    echo "✅ Sucessos: $sucessos<br>";
    echo "❌ Erros/Duplicados: $erros<br>";
    echo "<br><a href='admin.php'>← Voltar ao Painel Admin</a>";
    
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>
