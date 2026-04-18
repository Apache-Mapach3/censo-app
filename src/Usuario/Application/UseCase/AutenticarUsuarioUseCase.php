<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;

class AutenticarUsuarioUseCase {

    public function __construct(private UsuarioRepository $repository) {}

    public function execute(string $nombre, string $clavePlana): ?Usuario {

        if (trim($nombre) === '' || trim($clavePlana) === '') {
            return null;
        }

        // Intentar por nombre de usuario primero
        $usuario = $this->repository->findByNombre(trim($nombre));

        // Si no encuentra por nombre, intentar por correo (por si acaso)
        if (!$usuario) {
            $usuario = $this->repository->findByCorreo(trim($nombre));
        }

        if (!$usuario) {
            return null;
        }

        $claveDB = $usuario->getClave();

        // Detectar si la clave en DB es un hash bcrypt válido
        $esBcrypt = (strlen($claveDB) >= 60 && str_starts_with($claveDB, '$2'));

        if ($esBcrypt) {
            if (!password_verify($clavePlana, $claveDB)) {
                return null;
            }
        } else {
            // Clave guardada en texto plano (no debería ocurrir, pero cubrimos el caso)
            if ($clavePlana !== $claveDB) {
                return null;
            }
        }

        return $usuario;
    }
}