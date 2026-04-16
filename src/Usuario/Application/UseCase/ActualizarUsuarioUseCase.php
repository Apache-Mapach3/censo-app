<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;

class ActualizarUsuarioUseCase {

    public function __construct(private UsuarioRepository $repository) {}

    public function execute(int $id, string $nombre, string $correo, string $rol, string $nuevaClave = ''): void {

        $usuario = $this->repository->findById($id);
        if (!$usuario) {
            throw new \InvalidArgumentException("Usuario con id $id no encontrado");
        }

        $clave = $usuario->getClave();

        if (trim($nuevaClave) !== '') {
            if (strlen($nuevaClave) < 6) {
                throw new \InvalidArgumentException("La contraseña debe tener al menos 6 caracteres");
            }
            $clave = password_hash($nuevaClave, PASSWORD_DEFAULT);
        }

        $actualizado = new Usuario($id, $nombre, $clave, $rol, $correo);
        $this->repository->update($actualizado);
    }
}