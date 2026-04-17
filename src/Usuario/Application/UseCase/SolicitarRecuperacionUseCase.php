<?php
namespace App\Usuario\Application\UseCase;
use App\Usuario\Domain\Repository\UsuarioRepository;
use App\Usuario\Domain\Repository\TokenRecuperacionRepository;
use App\Usuario\Domain\Service\EmailSenderInterface; 
class SolicitarRecuperacionUseCase {
    public function __construct(
        private UsuarioRepository           $usuarioRepo,
        private TokenRecuperacionRepository $tokenRepo,
        private EmailSenderInterface        $emailSender 
    ) {}

    public function execute(string $correo, string $baseUrl): void {

        $usuario = $this->usuarioRepo->findByCorreo($correo);

        // No revelar si el correo existe por seguridad
        if (!$usuario) return;

        $this->tokenRepo->eliminarExpirados();

        $token  = bin2hex(random_bytes(32));
        $expira = new \DateTime('+1 hour');

        $this->tokenRepo->guardar($usuario->getId(), $token, $expira);

        // Construimos el enlace
        $enlace = $baseUrl . '/index.php?action=restablecer_clave&token=' . $token;

        // usa el servicio externo para enviar el correo
        $this->emailSender->enviarCorreoRecuperacion($usuario->getCorreo(), $enlace);
    }
}