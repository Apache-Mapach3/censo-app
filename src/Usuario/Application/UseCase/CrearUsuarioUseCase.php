<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;
use App\Organizacion\Domain\Repository\OrganizacionRepository; // <-- 1. Importamos el repositorio de la organización

class CrearUsuarioUseCase {

    //  Añadimos el repositorio de la organización al constructor
    public function __construct(
        private UsuarioRepository $repository,
        private OrganizacionRepository $organizacionRepository 
    ) {}

    // Agregamos $nombreOrg como quinto parámetro (por defecto vacío)
    public function execute(string $nombre, string $clavePlana, string $rol, string $correo, string $nombreOrg = ''): void {

        if (trim($nombre) === '' || trim($clavePlana) === '' || trim($rol) === '' || trim($correo) === '') {
            throw new \InvalidArgumentException("Datos invalidos. Por favor completa todos los campos.");
        }

        if ($this->repository->existsByNombre($nombre)) {
            throw new \InvalidArgumentException("El nombre de usuario '$nombre' ya esta en uso");
        }

        //  NUEVA LÓGICA: Buscar el ID de la organización si enviaron una
        $orgId = null;
        if (!empty(trim($nombreOrg))) {
            $orgId = $this->organizacionRepository->findByNombre($nombreOrg);
            
            if (!$orgId) {
                throw new \InvalidArgumentException("La organización '$nombreOrg' no existe. Verifica el nombre o pide a un Admin que la cree.");
            }
        }

        $claveHash = password_hash($clavePlana, PASSWORD_DEFAULT);
        
        // Le pasamos el $orgId al final del constructor de Usuario
        $usuario = new Usuario(null, $nombre, $claveHash, $rol, $correo, $orgId);
        
        $this->repository->save($usuario);
    }
}