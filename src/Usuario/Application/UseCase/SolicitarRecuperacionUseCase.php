<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Repository\UsuarioRepository;
use App\Usuario\Domain\Repository\TokenRecuperacionRepository;

class SolicitarRecuperacionUseCase {

    public function __construct(
        private UsuarioRepository           $usuarioRepo,
        private TokenRecuperacionRepository $tokenRepo
    ) {}

    public function execute(string $correo, string $baseUrl): void {

        $usuario = $this->usuarioRepo->findByCorreo($correo);

        // No revelar si el correo existe por seguridad
        if (!$usuario) return;

        $this->tokenRepo->eliminarExpirados();

        $token  = bin2hex(random_bytes(32));
        $expira = new \DateTime('+1 hour');

        $this->tokenRepo->guardar($usuario->getId(), $token, $expira);

        $enlace  = $baseUrl . '/index.php?action=restablecer_clave&token=' . $token;
        $asunto  = 'Recuperación de contraseña — CensoApp';
        $mensaje = "Hola {$usuario->getNombre()},\n\n"
            . "Recibimos una solicitud para restablecer tu contraseña.\n\n"
            . "Haz clic en el siguiente enlace (válido por 1 hora):\n"
            . $enlace . "\n\n"
            . "Si no solicitaste este cambio, ignora este mensaje.\n\n"
            . "CensoApp — Unicartagena";

        $cabeceras = "From: no-reply@censoapp.local\r\nContent-Type: text/plain; charset=UTF-8";

        mail($usuario->getCorreo(), $asunto, $mensaje, $cabeceras);
    }
}