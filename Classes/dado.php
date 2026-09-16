<?php

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