<?php
if (!defined('GAYME')) {
    http_response_code(403);
    exit('Acesso direto nao permitido.');
}

class Dado
{
    public function rolar(): int
    {
        return random_int(1, 6);
    }

    public function testar(int $valorAtributo): int
    {
        $resultado = $this->rolar() + $valorAtributo;
        return min($resultado, 6);
    }
}