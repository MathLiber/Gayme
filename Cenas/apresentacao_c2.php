<?php
require_once __DIR__ . '/../Classes/Cena.php';

return new Cena(
    imagem: 'img/cena02.jpg',
    texto: 'Uma faca enferrujada está cravada no batente da porta. Você tenta arrancá-la (Destreza).',
    atributoTeste: 'destreza',
    resultadosDado: [
        1 => 'des1_result1',
        2 => 'des1_result1',
        3 => 'des1_result2',
        4 => 'des1_result2',
        5 => 'des1_result3',
        6 => 'des1_result3',
    ]
);