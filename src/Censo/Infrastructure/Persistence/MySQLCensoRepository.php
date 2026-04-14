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
        $stmt->execute([
            'nombre' => $censo->getNombre(),
            'fecha' => $censo->getFecha()->format('Y-m-d'), // Convertimos DateTime a string de MySQL
            'pais' => $censo->getPais(),
            'departamento' => $censo->getDepartamento(),
            'ciudad' => $censo->getCiudad(),
            'casa' => $censo->getCasa(),
            'numHombres' => $censo->getNumHombres(),
            'numMujeres' => $censo->getNumMujeres(),
            'numAncianosHombres' => $censo->getNumAncianosHombres(),
            'numAncianasMujeres' => $censo->getNumAncianasMujeres(),
            'numNinos' => $censo->getNumNinos(),
            'numNinas' => $censo->getNumNinas(),
            'numHabitaciones' => $censo->getNumHabitaciones(),
            'numCamas' => $censo->getNumCamas(),
            'tieneAgua' => (int)$censo->getTieneAgua(), // Convertimos bool a TINYINT(1)
            'tieneLuz' => (int)$censo->getTieneLuz(),
            'tieneAlcantarillado' => (int)$censo->getTieneAlcantarillado(),
            'tieneGas' => (int)$censo->getTieneGas(),
            'tieneOtrosServicios' => (int)$censo->getTieneOtrosServicios(),
            'nombreSensador' => $censo->getNombreSensador()
        ]);
    }

    public function findById(int $id): ?Censo {
        // Logica idéntica al de usuario: SELECT * FROM censos WHERE id = :id
    
        return null; // Cambiar por el retorno del objeto instanciado
    }

    /** @return Censo[] */
    public function findAll(): array {
        // SELECT * FROM censos
        return [];
    }

    public function update(Censo $censo): void {
        // UPDATE censos SET ... WHERE id = :id
    }

    public function delete(int $id): void {
        $stmt = $this->connection->prepare("DELETE FROM censos WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}