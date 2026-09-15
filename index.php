<?php
session_start();
require __DIR__ . '/dado.php';

if (!isset($_SESSION['cena'])) {
    $_SESSION['cena'] = 'apresentacao_c1';
}

if (isset($_POST['ir'])) {
    $_SESSION['cena'] = $_POST['ir'];
    header('Location: index.php');
    exit;
}

if (isset($_POST['rolar'])) {
    include __DIR__ . '/Cenas/' . $_SESSION['cena'] . '.php';
    if (isset($dado)) {
        $numero = rolarDado();
        $_SESSION['cena'] = $dado[$numero];
    }
    header('Location: index.php');
    exit;
}

// Puxa a cena atual
include __DIR__ . '/Cenas/' . $_SESSION['cena'] . '.php';
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
    <img class="imagem-cena" src="<?= $imagem ?>" alt="">

    <div class="caixa-texto">
        <?= nl2br($texto) ?>
    </div>

    <?php if (isset($dado)): ?>
        <div class="acao">
            <form method="post">
                <button type="submit" name="rolar" value="1">Rolar o dado</button>
            </form>
        </div>
    <?php elseif (isset($proxima)): ?>
        <div class="acao">
            <form method="post">
                <input type="hidden" name="ir" value="<?= $proxima ?>">
                <button type="submit">Continuar</button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>