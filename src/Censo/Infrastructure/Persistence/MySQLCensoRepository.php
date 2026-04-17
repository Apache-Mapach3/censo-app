<?php

namespace App\Censo\Infrastructure\Persistence;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;
use PDO;

class MySQLCensoRepository implements CensoRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

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
            ':jefe'   => $censo->getJefeFamilia(),
            ':doc'    => $censo->getDocumento(),
            ':dir'    => $censo->getDireccion(),
            ':barrio' => $censo->getBarrio(),
            ':cant'   => $censo->getCantidadPersonas(),
            ':estrato'=> $censo->getEstrato(),
            ':obs'    => $censo->getObservaciones(),
            ':org_id' => $censo->getOrganizacionId(),
            ':nombre' => $censo->getNombre(),
            ':fecha'  => $censo->getFecha()->format('Y-m-d H:i:s'),
            ':pais'   => $censo->getPais(),
            ':depto'  => $censo->getDepartamento(),
            ':ciudad' => $censo->getCiudad(),
            ':casa'   => $censo->getCasa(),
            ':numH'   => $censo->getNumHombres(),
            ':numM'   => $censo->getNumMujeres(),
            ':numAH'  => $censo->getNumAncianosHombres(),
            ':numAM'  => $censo->getNumAncianasMujeres(),
            ':numNinos'=> $censo->getNumNinos(),
            ':numNinas'=> $censo->getNumNinas(),
            ':numHab'  => $censo->getNumHabitaciones(),
            ':numCamas'=> $censo->getNumCamas(),
            ':agua'    => (int)$censo->tieneAgua(),
            ':luz'     => (int)$censo->tieneLuz(),
            ':alcant'  => (int)$censo->tieneAlcantarillado(),
            ':gas'     => (int)$censo->tieneGas(),
            ':otros'   => (int)$censo->tieneOtrosServicios(),
            ':sensador'=> $censo->getNombreSensador()
        ]);
    }

    public function findById(int $id): ?Censo 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM censos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->mapRowToCenso($row);
    }

    public function findAll(): array 
    {
        $stmt = $this->pdo->query("SELECT * FROM censos ORDER BY fecha DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $censos = [];
        foreach ($rows as $row) {
            $censos[] = $this->mapRowToCenso($row);
        }
        
        return $censos;
    }

    public function update(Censo $censo): void 
    {
        // Actualizamos basándonos en el documento de identidad
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
                WHERE documento = :doc";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':jefe'   => $censo->getJefeFamilia(),
            ':dir'    => $censo->getDireccion(),
            ':barrio' => $censo->getBarrio(),
            ':cant'   => $censo->getCantidadPersonas(),
            ':estrato'=> $censo->getEstrato(),
            ':obs'    => $censo->getObservaciones(),
            ':org_id' => $censo->getOrganizacionId(),
            ':nombre' => $censo->getNombre(),
            ':fecha'  => $censo->getFecha()->format('Y-m-d H:i:s'),
            ':pais'   => $censo->getPais(),
            ':depto'  => $censo->getDepartamento(),
            ':ciudad' => $censo->getCiudad(),
            ':casa'   => $censo->getCasa(),
            ':numH'   => $censo->getNumHombres(),
            ':numM'   => $censo->getNumMujeres(),
            ':numAH'  => $censo->getNumAncianosHombres(),
            ':numAM'  => $censo->getNumAncianasMujeres(),
            ':numNinos'=> $censo->getNumNinos(),
            ':numNinas'=> $censo->getNumNinas(),
            ':numHab'  => $censo->getNumHabitaciones(),
            ':numCamas'=> $censo->getNumCamas(),
            ':agua'    => (int)$censo->tieneAgua(),
            ':luz'     => (int)$censo->tieneLuz(),
            ':alcant'  => (int)$censo->tieneAlcantarillado(),
            ':gas'     => (int)$censo->tieneGas(),
            ':otros'   => (int)$censo->tieneOtrosServicios(),
            ':sensador'=> $censo->getNombreSensador(),
            ':doc'    => $censo->getDocumento() 
        ]);
    }

    public function delete(int $id): void 
    {
        $stmt = $this->pdo->prepare("DELETE FROM censos WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Convierte un array asociativo de MySQL a una instancia de Censo
     */
    private function mapRowToCenso(array $row): Censo 
    {
        return new Censo(
            $row['jefe_familia'],
            $row['documento'],
            $row['direccion'],
            $row['barrio'],
            (int)$row['cantidad_personas'],
            $row['estrato'] !== null ? (int)$row['estrato'] : null,
            $row['observaciones'],
            (int)$row['organizacion_id'],
            $row['nombre'],
            new \DateTime($row['fecha']),
            $row['pais'],
            $row['departamento'],
            $row['ciudad'],
            $row['casa'],
            (int)$row['numHombres'],
            (int)$row['numMujeres'],
            (int)$row['numAncianosHombres'],
            (int)$row['numAncianasMujeres'],
            (int)$row['numNinos'],
            (int)$row['numNinas'],
            (int)$row['numHabitaciones'],
            (int)$row['numCamas'],
            (bool)$row['tieneAgua'],
            (bool)$row['tieneLuz'],
            (bool)$row['tieneAlcantarillado'],
            (bool)$row['tieneGas'],
            (bool)$row['tieneOtrosServicios'],
            $row['nombreSensador']
        );
    }
}