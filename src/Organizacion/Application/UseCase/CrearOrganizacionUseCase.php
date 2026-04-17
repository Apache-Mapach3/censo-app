<?php
namespace App\Organizacion\Application\UseCase;

use App\Organizacion\Domain\Model\Organizacion;
use App\Organizacion\Domain\Repository\OrganizacionRepository;
use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;

class CrearOrganizacionConAdminUseCase {

    public function __construct(
        private OrganizacionRepository $orgRepo,
        private UsuarioRepository      $usuarioRepo
    ) {}

    public function execute(
        string $nombreOrg,
        string $nombreAdmin,
        string $clavePlana,
        string $correo
    ): int {
        // Generar código único para la org a partir del nombre del admin
        $codigo = $this->generarCodigo($nombreAdmin);

        if ($this->orgRepo->findByCodigo($codigo)) {
            $codigo .= '_' . random_int(100, 999);
        }

        if ($this->usuarioRepo->existsByNombre($nombreAdmin)) {
            throw new \InvalidArgumentException("El nombre de usuario '$nombreAdmin' ya está en uso");
        }

        // 1. Crear la organización
        $org   = new Organizacion(null, $nombreOrg, $codigo);
        $orgId = $this->orgRepo->save($org);

        // 2. Crear el usuario admin ligado a esa org
        $hash    = password_hash($clavePlana, PASSWORD_DEFAULT);
        $usuario = new Usuario(null, $nombreAdmin, $hash, 'admin', $correo);
        $this->usuarioRepo->saveConOrg($usuario, $orgId);

        return $orgId;
    }

    private function generarCodigo(string $nombre): string {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $nombre));
        return substr($slug, 0, 30) . '_' . date('Y');
    }
}