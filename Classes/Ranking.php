<?php
if (!defined('GAYME')) {
    http_response_code(403);
    exit('Acesso direto nao permitido.');
}

class Ranking
{
    private ?PDO $conn;

    public function __construct(?PDO $conn)
    {
        $this->conn = $conn;
    }

    public function salvar(string $nome, int $pontos): bool
    {
        if ($this->conn === null) {
            return false;
        }

        try {
            $stmt = $this->conn->prepare(
                'INSERT INTO ranking (nome, pontos) VALUES (:nome, :pontos)'
            );
            return $stmt->execute([
                'nome'   => $nome,
                'pontos' => $pontos,
            ]);
        } catch (PDOException $ex) {
            return false;
        }
    }

    public function melhores(int $limite = 10): array
    {
        if ($this->conn === null) {
            return [];
        }

        try {
            $stmt = $this->conn->prepare(
                'SELECT nome, pontos FROM ranking ORDER BY pontos DESC LIMIT :limite'
            );
            $stmt->bindValue('limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return [];
        }
    }
}
