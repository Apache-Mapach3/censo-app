<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Censo — CensoApp</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #f4f6f9; margin: 0; padding: 40px; color: #0f1f3d; }
        .container { max-width: 800px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h2 { font-family: 'DM Serif Display', serif; font-size: 28px; margin-top: 0; margin-bottom: 20px; color: #0f1f3d; border-bottom: 2px solid #e8a020; display: inline-block; }
        h3 { font-size: 18px; color: #0d7377; border-bottom: 1px solid #dce2ea; padding-bottom: 5px; margin-top: 30px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px; }
        input[type="text"], input[type="number"], input[type="date"] { width: 100%; padding: 10px; border: 1px solid #dce2ea; border-radius: 8px; box-sizing: border-box; font-family: inherit; }
        .checkbox-group { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px; }
        .checkbox-item { display: flex; align-items: center; gap: 5px; font-size: 14px; }
        .btn-group { display: flex; gap: 15px; margin-top: 30px; }
        .btn-save { background: #0d7377; color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; flex: 1; font-size: 16px; }
        .btn-cancel { background: #8896a8; color: white; text-decoration: none; padding: 12px 20px; border-radius: 8px; text-align: center; flex: 1; font-size: 16px; font-weight: 600; }
        .btn-save:hover { background: #0a5a5d; }
        .btn-cancel:hover { background: #6b778c; }
    </style>
</head>
<body>

<div class="container">
    <h2>📝 Editar Censo</h2>
    
    <form action="index.php?action=actualizar_censo" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($censo->getId() ?? '') ?>">

        <h3>Datos Generales</h3>
        <div class="grid-2">
            <div class="form-group">
                <label for="nombre">Nombre del Jefe de Hogar / Familia</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($censo->getNombre()) ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha del Censo</label>
                <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($censo->getFecha()->format('Y-m-d')) ?>" required>
            </div>
            <div class="form-group">
                <label for="nombreSensador">Nombre del Sensador</label>
                <input type="text" id="nombreSensador" name="nombreSensador" value="<?= htmlspecialchars($censo->getNombreSensador()) ?>" required>
            </div>
        </div>

        <h3>Ubicación</h3>
        <div class="grid-2">
            <div class="form-group">
                <label for="pais">País</label>
                <input type="text" id="pais" name="pais" value="<?= htmlspecialchars($censo->getPais()) ?>" required>
            </div>
            <div class="form-group">
                <label for="departamento">Departamento</label>
                <input type="text" id="departamento" name="departamento" value="<?= htmlspecialchars($censo->getDepartamento()) ?>" required>
            </div>
            <div class="form-group">
                <label for="ciudad">Ciudad / Municipio</label>
                <input type="text" id="ciudad" name="ciudad" value="<?= htmlspecialchars($censo->getCiudad()) ?>" required>
            </div>
            <div class="form-group">
                <label for="casa">Dirección / Casa</label>
                <input type="text" id="casa" name="casa" value="<?= htmlspecialchars($censo->getCasa()) ?>" required>
            </div>
        </div>

        <h3>Demografía de la Vivienda</h3>
        <div class="grid-3">
            <div class="form-group">
                <label for="numHombres">Hombres (Adultos)</label>
                <input type="number" id="numHombres" name="numHombres" min="0" value="<?= htmlspecialchars($censo->getNumHombres()) ?>" required>
            </div>
            <div class="form-group">
                <label for="numMujeres">Mujeres (Adultas)</label>
                <input type="number" id="numMujeres" name="numMujeres" min="0" value="<?= htmlspecialchars($censo->getNumMujeres()) ?>" required>
            </div>
            <div class="form-group">
                <label for="numAncianosHombres">Ancianos (Hombres)</label>
                <input type="number" id="numAncianosHombres" name="numAncianosHombres" min="0" value="<?= htmlspecialchars($censo->getNumAncianosHombres()) ?>" required>
            </div>
            <div class="form-group">
                <label for="numAncianasMujeres">Ancianas (Mujeres)</label>
                <input type="number" id="numAncianasMujeres" name="numAncianasMujeres" min="0" value="<?= htmlspecialchars($censo->getNumAncianasMujeres()) ?>" required>
            </div>
            <div class="form-group">
                <label for="numNinos">Niños</label>
                <input type="number" id="numNinos" name="numNinos" min="0" value="<?= htmlspecialchars($censo->getNumNinos()) ?>" required>
            </div>
            <div class="form-group">
                <label for="numNinas">Niñas</label>
                <input type="number" id="numNinas" name="numNinas" min="0" value="<?= htmlspecialchars($censo->getNumNinas()) ?>" required>
            </div>
        </div>

        <h3>Características de la Vivienda</h3>
        <div class="grid-2">
            <div class="form-group">
                <label for="numHabitaciones">Número de Habitaciones</label>
                <input type="number" id="numHabitaciones" name="numHabitaciones" min="1" value="<?= htmlspecialchars($censo->getNumHabitaciones()) ?>" required>
            </div>
            <div class="form-group">
                <label for="numCamas">Número de Camas</label>
                <input type="number" id="numCamas" name="numCamas" min="0" value="<?= htmlspecialchars($censo->getNumCamas()) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Servicios Básicos Disponibles:</label>
            <div class="checkbox-group">
                <label class="checkbox-item"><input type="hidden" name="tieneAgua" value="0"><input type="checkbox" name="tieneAgua" value="1" <?= $censo->getTieneAgua() ? 'checked' : '' ?>> Agua Potable</label>
                <label class="checkbox-item"><input type="hidden" name="tieneLuz" value="0"><input type="checkbox" name="tieneLuz" value="1" <?= $censo->getTieneLuz() ? 'checked' : '' ?>> Energía Eléctrica</label>
                <label class="checkbox-item"><input type="hidden" name="tieneAlcantarillado" value="0"><input type="checkbox" name="tieneAlcantarillado" value="1" <?= $censo->getTieneAlcantarillado() ? 'checked' : '' ?>> Alcantarillado</label>
                <label class="checkbox-item"><input type="hidden" name="tieneGas" value="0"><input type="checkbox" name="tieneGas" value="1" <?= $censo->getTieneGas() ? 'checked' : '' ?>> Gas Natural</label>
                <label class="checkbox-item"><input type="hidden" name="tieneOtrosServicios" value="0"><input type="checkbox" name="tieneOtrosServicios" value="1" <?= $censo->getTieneOtrosServicios() ? 'checked' : '' ?>> Otros Servicios</label>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-save">💾 Guardar Cambios</button>
            <a href="index.php?action=listar_censos" class="btn-cancel">❌ Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>