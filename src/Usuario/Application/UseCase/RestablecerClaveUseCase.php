<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;
use App\Usuario\Domain\Repository\TokenRecuperacionRepository;

class RestablecerClaveUseCase {

    public function __construct(
        private UsuarioRepository           $usuarioRepo,
        private TokenRecuperacionRepository $tokenRepo
    ) {}

    public function execute(string $token, string $nuevaClave): void {

        if (strlen($nuevaClave) < 6) {
            throw new \InvalidArgumentException("La contraseña debe tener al menos 6 caracteres");
        }

        $datos = $this->tokenRepo->findByToken($token);

        if (!$datos || $datos['usado']) {
            throw new \InvalidArgumentException("Token inválido o ya utilizado");
        }

        if (new \DateTime() > new \DateTime($datos['expira_en'])) {
            throw new \InvalidArgumentException("El enlace ha expirado. Solicita uno nuevo");
        }

        $usuario = $this->usuarioRepo->findById((int)$datos['usuario_id']);
        if (!$usuario) {
            throw new \InvalidArgumentException("Usuario no encontrado");
        }

        $actualizado = new Usuario(
            $usuario->getId(),
            $usuario->getNombre(),
            password_hash($nuevaClave, PASSWORD_DEFAULT),
            $usuario->getRol(),
            $usuario->getCorreo()
        );

        $this->usuarioRepo->update($actualizado);
        $this->tokenRepo->marcarUsado($token);
    }
}