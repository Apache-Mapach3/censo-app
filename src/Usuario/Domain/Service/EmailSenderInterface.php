<?php
namespace App\Usuario\Domain\Service;

interface EmailSenderInterface {
    public function enviarCorreoRecuperacion(string $emailDestino, string $token): bool;
}