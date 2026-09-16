<?php
require_once __DIR__ . '/../Classes/Cena.php';

return new Cena(
    imagem: 'img/cena02.jpg',
    texto: 'No fundo da tumba, símbolos antigos cobrem as paredes -- registros deixados pelos Eminentes. Você tenta decifrá-los (teste de Observação).',
    atributoTeste: 'observacao',
    resultadosDado: [
        1 => 'encerramento1',
        2 => 'encerramento1',
        3 => 'encerramento2',
        4 => 'encerramento2',
        5 => 'encerramento3',
        6 => 'encerramento3',
    ]
);