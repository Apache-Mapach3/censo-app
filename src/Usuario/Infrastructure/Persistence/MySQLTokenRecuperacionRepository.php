<?php

namespace App\Usuario\Infrastructure\Persistence;

use App\Usuario\Domain\Repository\TokenRecuperacionRepository;
use PDO;

class MySQLTokenRecuperacionRepository implements TokenRecuperacionRepository {

    public function __construct(private PDO $pdo) {}

    public function guardar(int $usuarioId, string $token, \DateTime $expira): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO tokens_recuperacion (usuario_id, token, expira_en) VALUES (?, ?, ?)"
        );
        $stmt->execute([$usuarioId, $token, $expira->format('Y-m-d H:i:s')]);
    }

    public function findByToken(string $token): ?array {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM tokens_recuperacion WHERE token = ?"
        );
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function marcarUsado(string $token): void {
        $stmt = $this->pdo->prepare(
            "UPDATE tokens_recuperacion SET usado = 1 WHERE token = ?"
        );
        $stmt->execute([$token]);
    }

    public function eliminarExpirados(): void {
        $this->pdo->exec("DELETE FROM tokens_recuperacion WHERE expira_en < NOW()");
    }
}