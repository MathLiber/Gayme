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

// Criação de personagem
if (!isset($_SESSION['personagem'])) {

    $erroDistribuicao = null;

    if (isset($_POST['criar_personagem'])) {
        $observacao = (int) ($_POST['observacao'] ?? 0);
        $destreza = (int) ($_POST['destreza'] ?? 0);
        $forca = (int) ($_POST['forca'] ?? 0);
        $soma = $observacao + $destreza + $forca;

        if ($soma <= 5 && $observacao >= 0 && $destreza >= 0 && $forca >= 0) {
            $personagem = new Personagem($observacao, $destreza, $forca);
            $_SESSION['personagem'] = $personagem->paraArray();
            $_SESSION['cena'] = 'apresentacao_c1';
            header('Location: index.php');
            exit;
        }

        $erroDistribuicao = 'A soma dos pontos não pode passar de 5.';
    }
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
                <h2>Distribua seus atributos</h2>
                <p>Você tem <strong>5 pontos</strong> pra distribuir entre Observação, Destreza e Força.</p>

                <?php if ($erroDistribuicao): ?>
                    <p class="erro"><?= $erroDistribuicao ?></p>
                <?php endif; ?>

                <form method="post">
                    <label>Observação
                        <input type="number" name="observacao" min="0" max="5" value="0">
                    </label>
                    <label>Destreza
                        <input type="number" name="destreza" min="0" max="5" value="0">
                    </label>
                    <label>Força
                        <input type="number" name="forca" min="0" max="5" value="0">
                    </label>
                    <button type="submit" name="criar_personagem" value="1">Começar jogo</button>
                </form>
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

// Botão Continuar
if (isset($_POST['continuar']) && $cena->proxima !== null) {
    $_SESSION['cena'] = $cena->proxima;
    header('Location: index.php');
    exit;
}

// Botão Rolar
if (isset($_POST['rolar']) && $cena->temTeste()) {
    $valorAtributo = $personagem->getAtributo($cena->atributoTeste);
    $numeroFinal = $dado->testar($valorAtributo);
    $_SESSION['cena'] = $cena->resultadosDado[$numeroFinal];
    header('Location: index.php');
    exit;
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
            <form method="post">
                <button type="submit" name="reiniciar" value="1">Reiniciar</button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>