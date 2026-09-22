<?php
define('GAYME', true);

session_start();

require __DIR__ . '/Classes/Personagem.php';
require __DIR__ . '/Classes/Dado.php';
require __DIR__ . '/Classes/Cena.php';

if (isset($_POST['reiniciar'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}


if (!isset($_SESSION['personagem'])) {

    $erroDistribuicao = null;

    if (isset($_POST['criar_personagem'])) {
        $jogador = trim((string) ($_POST['jogador'] ?? ''));
        $destreza = (int) ($_POST['destreza'] ?? 0);
        $forca = (int) ($_POST['forca'] ?? 0);
        $soma = $destreza + $forca;

        if ($jogador === '') {
            $erroDistribuicao = 'Digite um nome (ele aparece no ranking).';
        } elseif ($soma > 3 || $destreza < 0 || $forca < 0) {
            $erroDistribuicao = 'A soma dos pontos não pode passar de 3.';
        } else {
            $personagem = new Personagem($destreza, $forca);
            $_SESSION['personagem'] = $personagem->paraArray();
            $_SESSION['jogador'] = $jogador;
            $_SESSION['pontuacao'] = 0;
            $_SESSION['cena'] = 'apresentacao_c1';
            header('Location: index.php');
            exit;
        }
    }

    
    require __DIR__ . '/conexao.php';
    require __DIR__ . '/Classes/Ranking.php';
    $ranking = new Ranking($conn);
    $melhores = $ranking->melhores(10);
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Criação de Personagem</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="tela tela-criacao">
            <div class="caixa-texto">
                <div class="criacao-conteudo">
                <div class="criacao-form">
                <h2>Distribua seus atributos</h2>
                <p>Você tem <strong>3 pontos</strong> pra distribuir entre Observação, Destreza e Força.</p>

                <?php if ($erroDistribuicao): ?>
                    <p class="erro"><?= $erroDistribuicao ?></p>
                <?php endif; ?>

                <form method="post">
                    <label>Seu nome (aparece no ranking)
                        <input type="text" name="jogador" maxlength="40" value="<?= $jogador ?? '' ?>">
                    </label>
                    <label>Destreza
                        <input type="number" name="destreza" min="0" max="3" value="0">
                    </label>
                    <label>Força
                        <input type="number" name="forca" min="0" max="3" value="0">
                    </label>
                    <button type="submit" name="criar_personagem" value="1">Começar jogo</button>
                </form>
                </div>

                <div class="ranking-painel">
                    <h3>Melhores pontuações</h3>
                    <table>
                        <thead>
                            <tr><th>#</th><th>Nome</th><th>Pontos</th></tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < 10; $i++): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= $melhores[$i]['nome'] ?? '-' ?></td>
                                    <td><?= $melhores[$i]['pontos'] ?? '-' ?></td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$personagem = Personagem::deArray($_SESSION['personagem']);
$dado = new Dado();

if (!isset($_SESSION['cena'])) {
    $_SESSION['cena'] = 'apresentacao_c1';
}

$nomeCena = $_SESSION['cena'];
if (!is_file(__DIR__ . '/Cenas/' . $nomeCena . '.php')) {
    $nomeCena = 'apresentacao_c1';
    $_SESSION['cena'] = $nomeCena;
}

$cena = include __DIR__ . '/Cenas/' . $nomeCena . '.php';


if (isset($_POST['continuar']) && $cena->proxima !== null) {
    $_SESSION['cena'] = $cena->proxima;
    header('Location: index.php');
    exit;
}


if (isset($_POST['rolar']) && $cena->temTeste()) {
    $valorAtributo = $personagem->getAtributo($cena->atributoTeste);
    $numeroFinal = $dado->testar($valorAtributo);

    if ($numeroFinal <= 2) {
        $_SESSION['pontuacao'] = ($_SESSION['pontuacao'] ?? 0) - 100;
    } elseif ($numeroFinal <= 4) {
        $_SESSION['pontuacao'] = ($_SESSION['pontuacao'] ?? 0) + 30;
    } else {
        $_SESSION['pontuacao'] = ($_SESSION['pontuacao'] ?? 0) + 50;
    }

    $_SESSION['cena'] = $cena->resultadosDado[$numeroFinal];
    header('Location: index.php');
    exit;
}


if ($cena->ehFinal() && !isset($_SESSION['pontuacao_salva'])) {
    require __DIR__ . '/conexao.php';
    require __DIR__ . '/Classes/Ranking.php';

    $ranking = new Ranking($conn);
    $ranking->salvar($_SESSION['jogador'] ?? 'Jogador', $_SESSION['pontuacao'] ?? 0);
    $_SESSION['pontuacao_salva'] = true;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Jogo</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="tela">
    <img class="imagem-cena" src="<?= $cena->imagem ?>" alt="">

    <div class="caixa-texto">
        <?= $cena->texto ?>
    </div>

    <?php if ($cena->temTeste()): ?>
        <div class="acao">
            <form method="post">
                <button type="submit" name="rolar" value="1">Rolar o dado</button>
            </form>
        </div>
    <?php elseif ($cena->proxima !== null): ?>
        <div class="acao">
            <form method="post">
                <button type="submit" name="continuar" value="1">Continuar</button>
            </form>
        </div>
    <?php else: ?>
        <!-- sem próxima cena e sem teste -->
        <div class="acao">
            <p class="pontuacao-final">Pontuação final: <?= $_SESSION['pontuacao'] ?? 0 ?></p>
            <form method="post">
                <button type="submit" name="reiniciar" value="1">Reiniciar</button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
