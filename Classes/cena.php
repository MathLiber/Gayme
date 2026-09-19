<?php
if (!defined('GAYME')) {
    http_response_code(403);
    exit('Acesso direto nao permitido.');
}

class Cena
{
    public string $imagem;
    public string $texto;
    public ?string $proxima;
    public ?string $atributoTeste;
    public ?array $resultadosDado;

    public function __construct(
        string $imagem,
        string $texto,
        ?string $proxima = null,
        ?string $atributoTeste = null,
        ?array $resultadosDado = null
    ) {
        $this->imagem = $imagem;
        $this->texto = $texto;
        $this->proxima = $proxima;
        $this->atributoTeste = $atributoTeste;
        $this->resultadosDado = $resultadosDado;
    }

    public function temTeste(): bool
    {
        return $this->atributoTeste !== null;
    }

    public function ehFinal(): bool
    {
        return $this->proxima === null && !$this->temTeste();
    }
}