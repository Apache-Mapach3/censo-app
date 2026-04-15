<?php

namespace App\Censo\Infrastructure\Persistence;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;
use PDO;

class MySQLCensoRepository implements CensoRepository {

    public function __construct(private PDO $connection) {}

    public function save(Censo $censo): void {
        $sql = "INSERT INTO censos (
            nombre, fecha, pais, departamento, ciudad, casa,
            numHombres, numMujeres, numAncianosHombres, numAncianasMujeres,
            numNinos, numNinas, numHabitaciones, numCamas,
            tieneAgua, tieneLuz, tieneAlcantarillado, tieneGas,
            tieneOtrosServicios, nombreSensador
        ) VALUES (
            :nombre, :fecha, :pais, :departamento, :ciudad, :casa,
            :numHombres, :numMujeres, :numAncianosHombres, :numAncianasMujeres,
            :numNinos, :numNinas, :numHabitaciones, :numCamas,
            :tieneAgua, :tieneLuz, :tieneAlcantarillado, :tieneGas,
            :tieneOtrosServicios, :nombreSensador
        )";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($this->censoToArray($censo));
    }

    public function findById(int $id): ?Censo {
        $stmt = $this->connection->prepare("SELECT * FROM censos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return $this->rowToCenso($row);
    }

    /** @return Censo[] */
    public function findAll(): array {
        $stmt = $this->connection->query("SELECT * FROM censos ORDER BY id DESC");
        $censos = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $censos[] = $this->rowToCenso($row);
        }

        return $censos;
    }

    public function update(Censo $censo): void {
        $sql = "UPDATE censos SET
            nombre              = :nombre,
            fecha               = :fecha,
            pais                = :pais,
            departamento        = :departamento,
            ciudad              = :ciudad,
            casa                = :casa,
            numHombres          = :numHombres,
            numMujeres          = :numMujeres,
            numAncianosHombres  = :numAncianosHombres,
            numAncianasMujeres  = :numAncianasMujeres,
            numNinos            = :numNinos,
            numNinas            = :numNinas,
            numHabitaciones     = :numHabitaciones,
            numCamas            = :numCamas,
            tieneAgua           = :tieneAgua,
            tieneLuz            = :tieneLuz,
            tieneAlcantarillado = :tieneAlcantarillado,
            tieneGas            = :tieneGas,
            tieneOtrosServicios = :tieneOtrosServicios,
            nombreSensador      = :nombreSensador
        WHERE id = :id";

        $datos = $this->censoToArray($censo);
        $datos['id'] = $censo->getId();

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($datos);
    }

    public function delete(int $id): void {
        $stmt = $this->connection->prepare("DELETE FROM censos WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    //Helpers privados 

    private function censoToArray(Censo $censo): array {
        return [
            'nombre'              => $censo->getNombre(),
            'fecha'               => $censo->getFecha()->format('Y-m-d'),
            'pais'                => $censo->getPais(),
            'departamento'        => $censo->getDepartamento(),
            'ciudad'              => $censo->getCiudad(),
            'casa'                => $censo->getCasa(),
            'numHombres'          => $censo->getNumHombres(),
            'numMujeres'          => $censo->getNumMujeres(),
            'numAncianosHombres'  => $censo->getNumAncianosHombres(),
            'numAncianasMujeres'  => $censo->getNumAncianasMujeres(),
            'numNinos'            => $censo->getNumNinos(),
            'numNinas'            => $censo->getNumNinas(),
            'numHabitaciones'     => $censo->getNumHabitaciones(),
            'numCamas'            => $censo->getNumCamas(),
            'tieneAgua'           => (int)$censo->getTieneAgua(),
            'tieneLuz'            => (int)$censo->getTieneLuz(),
            'tieneAlcantarillado' => (int)$censo->getTieneAlcantarillado(),
            'tieneGas'            => (int)$censo->getTieneGas(),
            'tieneOtrosServicios' => (int)$censo->getTieneOtrosServicios(),
            'nombreSensador'      => $censo->getNombreSensador(),
        ];
    }

    private function rowToCenso(array $row): Censo {
        return new Censo(
            (int)$row['id'],
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