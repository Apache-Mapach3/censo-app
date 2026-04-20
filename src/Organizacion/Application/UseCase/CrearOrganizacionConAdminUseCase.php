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

    public function execute(string $nombreOrg, string $nombreAdmin, string $clavePlana, string $correo): void
    {
        // Validar que no lleguen vacíos
        if (empty($nombreAdmin) || empty($nombreOrg)) {
            throw new \InvalidArgumentException("El nombre del administrador y de la organización son obligatorios.");
        }



        $slugBase = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $nombreOrg)));
        $codigoUnico = $slugBase . '_' . uniqid(); 
        $organizacion = new Organizacion(null, $nombreOrg, $codigoUnico);
        
        // Guardar y obtener el ID generado
        $orgId = $this->organizacionRepository->save($organizacion);


        $claveHash = password_hash($clavePlana, PASSWORD_DEFAULT);

        // IMPORTANTE: Asegúrate de que el constructor de Usuario esté en este orden: id, nombre, correo, clave, rol, organizacion_id
        $admin = new \App\Usuario\Domain\Model\Usuario(null, $nombreAdmin, $correo, $claveHash, 'admin', $orgId);
        
        // Guardar el usuario
        $this->usuarioRepository->save($admin);
    }

    private function generarCodigo(string $nombre): string {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $nombre));
        return substr($slug, 0, 30) . '_' . date('Y');
    }
}