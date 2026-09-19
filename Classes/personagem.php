<?php
if (!defined('GAYME')) {
    http_response_code(403);
    exit('Acesso direto nao permitido.');
}

class Personagem
{
    private int $observacao;
    private int $destreza;
    private int $forca;

    public function __construct(int $observacao = 0, int $destreza = 0, int $forca = 0)
    {
        $this->observacao = $observacao;
        $this->destreza = $destreza;
        $this->forca = $forca;
    }

    public function getAtributo(string $nome): int
    {
        return match ($nome) {
            'observacao' => $this->observacao,
            'destreza' => $this->destreza,
            'forca' => $this->forca,
            default => 0,
        };
    }

    // Forma de ir e voltar da sessão
    public function paraArray(): array
    {
        return [
            'observacao' => $this->observacao,
            'destreza' => $this->destreza,
            'forca' => $this->forca,
        ];
    }

    public static function deArray(array $dados): self
    {
        return new self(
            $dados['observacao'] ?? 0,
            $dados['destreza'] ?? 0,
            $dados['forca'] ?? 0
        );
    }
}