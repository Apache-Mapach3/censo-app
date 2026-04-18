<?php

namespace App\Censo\Infrastructure\Persistence;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;
use PDO;

class MySQLCensoRepository implements CensoRepository
{
    public function __construct(private PDO $pdo) {}

    public function save(Censo $censo): void
    {
        $sql = "INSERT INTO censos (
                    jefe_familia, documento, direccion, barrio, cantidad_personas, estrato,
                    observaciones, organizacion_id, nombre, fecha, pais, departamento,
                    ciudad, casa, numHombres, numMujeres, numAncianosHombres,
                    numAncianasMujeres, numNinos, numNinas, numHabitaciones, numCamas,
                    tieneAgua, tieneLuz, tieneAlcantarillado, tieneGas,
                    tieneOtrosServicios, nombreSensador
                ) VALUES (
                    :jefe, :doc, :dir, :barrio, :cant, :estrato, :obs, :org_id, :nombre,
                    :fecha, :pais, :depto, :ciudad, :casa, :numH, :numM, :numAH, :numAM,
                    :numNinos, :numNinas, :numHab, :numCamas, :agua, :luz, :alcant, :gas,
                    :otros, :sensador
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':jefe'    => $censo->getJefeFamilia(),
            ':doc'     => $censo->getDocumento(),
            ':dir'     => $censo->getDireccion(),
            ':barrio'  => $censo->getBarrio(),
            ':cant'    => $censo->getCantidadPersonas(),
            ':estrato' => $censo->getEstrato(),
            ':obs'     => $censo->getObservaciones(),
            ':org_id'  => $censo->getOrganizacionId(),
            ':nombre'  => $censo->getNombre(),
            ':fecha'   => $censo->getFecha()->format('Y-m-d H:i:s'),
            ':pais'    => $censo->getPais(),
            ':depto'   => $censo->getDepartamento(),
            ':ciudad'  => $censo->getCiudad(),
            ':casa'    => $censo->getCasa(),
            ':numH'    => $censo->getNumHombres(),
            ':numM'    => $censo->getNumMujeres(),
            ':numAH'   => $censo->getNumAncianosHombres(),
            ':numAM'   => $censo->getNumAncianasMujeres(),
            ':numNinos' => $censo->getNumNinos(),
            ':numNinas' => $censo->getNumNinas(),
            ':numHab'  => $censo->getNumHabitaciones(),
            ':numCamas' => $censo->getNumCamas(),
            ':agua'    => (int)$censo->tieneAgua(),
            ':luz'     => (int)$censo->tieneLuz(),
            ':alcant'  => (int)$censo->tieneAlcantarillado(),
            ':gas'     => (int)$censo->tieneGas(),
            ':otros'   => (int)$censo->tieneOtrosServicios(),
            ':sensador' => $censo->getNombreSensador(),
        ]);
    }

    public function findById(int $id): ?Censo
    {
        $stmt = $this->pdo->prepare("SELECT * FROM censos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToCenso($row) : null;
    }

    public function findAll(?int $orgId = null): array
    {
        if ($orgId !== null) {
            $stmt = $this->pdo->prepare(
                "SELECT * FROM censos WHERE organizacion_id = :org ORDER BY fecha DESC"
            );
            $stmt->execute([':org' => $orgId]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM censos ORDER BY fecha DESC");
        }

        $censos = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $censos[] = $this->mapRowToCenso($row);
        }
        return $censos;
    }

    public function update(Censo $censo): void
    {
        $sql = "UPDATE censos SET
                    jefe_familia = :jefe, direccion = :dir, barrio = :barrio,
                    cantidad_personas = :cant, estrato = :estrato, observaciones = :obs,
                    organizacion_id = :org_id, nombre = :nombre, fecha = :fecha,
                    pais = :pais, departamento = :depto, ciudad = :ciudad, casa = :casa,
                    numHombres = :numH, numMujeres = :numM, numAncianosHombres = :numAH,
                    numAncianasMujeres = :numAM, numNinos = :numNinos, numNinas = :numNinas,
                    numHabitaciones = :numHab, numCamas = :numCamas, tieneAgua = :agua,
                    tieneLuz = :luz, tieneAlcantarillado = :alcant, tieneGas = :gas,
                    tieneOtrosServicios = :otros, nombreSensador = :sensador
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':jefe'    => $censo->getJefeFamilia(),
            ':dir'     => $censo->getDireccion(),
            ':barrio'  => $censo->getBarrio(),
            ':cant'    => $censo->getCantidadPersonas(),
            ':estrato' => $censo->getEstrato(),
            ':obs'     => $censo->getObservaciones(),
            ':org_id'  => $censo->getOrganizacionId(),
            ':nombre'  => $censo->getNombre(),
            ':fecha'   => $censo->getFecha()->format('Y-m-d H:i:s'),
            ':pais'    => $censo->getPais(),
            ':depto'   => $censo->getDepartamento(),
            ':ciudad'  => $censo->getCiudad(),
            ':casa'    => $censo->getCasa(),
            ':numH'    => $censo->getNumHombres(),
            ':numM'    => $censo->getNumMujeres(),
            ':numAH'   => $censo->getNumAncianosHombres(),
            ':numAM'   => $censo->getNumAncianasMujeres(),
            ':numNinos' => $censo->getNumNinos(),
            ':numNinas' => $censo->getNumNinas(),
            ':numHab'  => $censo->getNumHabitaciones(),
            ':numCamas' => $censo->getNumCamas(),
            ':agua'    => (int)$censo->tieneAgua(),
            ':luz'     => (int)$censo->tieneLuz(),
            ':alcant'  => (int)$censo->tieneAlcantarillado(),
            ':gas'     => (int)$censo->tieneGas(),
            ':otros'   => (int)$censo->tieneOtrosServicios(),
            ':sensador' => $censo->getNombreSensador(),
            ':id'      => $censo->getId(),
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM censos WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    private function mapRowToCenso(array $row): Censo
    {
        return new Censo(
            (int)$row['id'],                                        // FIX: id estaba ausente
            $row['jefe_familia']      ?? '',
            $row['documento']         ?? '',
            $row['direccion']         ?? '',
            $row['barrio']            ?? '',
            (int)($row['cantidad_personas'] ?? 0),
            isset($row['estrato']) && $row['estrato'] !== null ? (int)$row['estrato'] : null,
            $row['observaciones']     ?? null,
            (int)($row['organizacion_id'] ?? 0),
            $row['nombre']            ?? '',
            new \DateTime($row['fecha']),
            $row['pais']              ?? '',
            $row['departamento']      ?? '',
            $row['ciudad']            ?? '',
            $row['casa']              ?? '',
            (int)($row['numHombres']         ?? 0),
            (int)($row['numMujeres']         ?? 0),
            (int)($row['numAncianosHombres'] ?? 0),
            (int)($row['numAncianasMujeres'] ?? 0),
            (int)($row['numNinos']           ?? 0),
            (int)($row['numNinas']           ?? 0),
            (int)($row['numHabitaciones']    ?? 1),
            (int)($row['numCamas']           ?? 0),
            (bool)$row['tieneAgua'],
            (bool)$row['tieneLuz'],
            (bool)$row['tieneAlcantarillado'],
            (bool)$row['tieneGas'],
            (bool)$row['tieneOtrosServicios'],
            $row['nombreSensador']    ?? ''
        );
    }
}