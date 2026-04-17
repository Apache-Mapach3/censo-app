<?php

namespace App\Censo\Infrastructure\Persistence;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;
use PDO;

class MySQLCensoRepository implements CensoRepository {

    public function __construct(private PDO $connection) {}

<<<<<<< refactor
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
=======
    public function save(Censo $censo, int $orgId): void {
        $sql = "INSERT INTO censos (
            jefe_familia, documento, direccion, barrio, cantidad_personas, estrato, observaciones, organizacion_id
        ) VALUES (
            :jefe_familia, :documento, :direccion, :barrio, :cantidad_personas, :estrato, :observaciones, :organizacion_id
        )";

        $stmt = $this->connection->prepare($sql);
        
        // Ejecutamos pasando los datos directamente desde el modelo
        $stmt->execute([
            'jefe_familia'      => $censo->getJefeFamilia(),
            'documento'         => $censo->getDocumento(),
            'direccion'         => $censo->getDireccion(),
            'barrio'            => $censo->getBarrio(),
            'cantidad_personas' => $censo->getCantidadPersonas(),
            'estrato'           => $censo->getEstrato(),
            'observaciones'     => $censo->getObservaciones(),
            'organizacion_id'   => $orgId
        ]);
>>>>>>> local
    }

    public function findById(int $id): ?Censo {
        $stmt = $this->connection->prepare("SELECT * FROM censos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->rowToCenso($row) : null;
    }

<<<<<<< refactor
    /** @return Censo[] */
    public function findAll(): array {
        $stmt = $this->connection->query("SELECT * FROM censos ORDER BY id DESC");
        $censos = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $censos[] = $this->rowToCenso($row);
        }

=======
    public function findAll(?int $orgId = null): array {
        if ($orgId !== null) {
            $stmt = $this->connection->prepare("SELECT * FROM censos WHERE organizacion_id = :org ORDER BY id DESC");
            $stmt->execute(['org' => $orgId]);
        } else {
            $stmt = $this->connection->query("SELECT * FROM censos ORDER BY id DESC");
        }

        $censos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $censos[] = $this->rowToCenso($row);
        }
>>>>>>> local
        return $censos;
    }

    public function update(Censo $censo): void {
        $sql = "UPDATE censos SET
            jefe_familia      = :jefe_familia,
            documento         = :documento,
            direccion         = :direccion,
            barrio            = :barrio,
            cantidad_personas = :cantidad_personas,
            estrato           = :estrato,
            observaciones     = :observaciones
        WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'id'                => $censo->getId(),
            'jefe_familia'      => $censo->getJefeFamilia(),
            'documento'         => $censo->getDocumento(),
            'direccion'         => $censo->getDireccion(),
            'barrio'            => $censo->getBarrio(),
            'cantidad_personas' => $censo->getCantidadPersonas(),
            'estrato'           => $censo->getEstrato(),
            'observaciones'     => $censo->getObservaciones()
        ]);
    }

    public function delete(int $id): void {
        $stmt = $this->connection->prepare("DELETE FROM censos WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    private function rowToCenso(array $row): Censo {
        return new Censo(
            (int)$row['id'],
            $row['jefe_familia'],
            $row['documento'],
            $row['direccion'],
            $row['barrio'],
            (int)$row['cantidad_personas'],
            (int)$row['estrato'],
            $row['observaciones'],
            (int)$row['organizacion_id']
        );
    }
}