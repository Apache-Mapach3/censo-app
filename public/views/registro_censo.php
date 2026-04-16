<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Censo — CensoApp</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --navy:   #0f1f3d;
            --teal:   #0d7377;
            --gold:   #e8a020;
            --light:  #f4f6f9;
            --muted:  #8896a8;
            --white:  #ffffff;
            --radius: 10px;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--light); color: var(--navy); }
        .topbar {
            background: var(--navy); color: var(--white);
            padding: 14px 32px; display: flex; align-items: center; justify-content: space-between;
        }
        .topbar .brand { font-family: 'DM Serif Display', serif; font-size: 20px; display: flex; align-items: center; gap: 10px; }
        .topbar a { color: var(--gold); text-decoration: none; font-size: 13px; font-weight: 600; }
        .container { max-width: 760px; margin: 36px auto; padding: 0 16px; }
        .card { background: var(--white); border-radius: 14px; padding: 36px; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
        h2 { font-family: 'DM Serif Display', serif; font-size: 26px; margin-bottom: 4px; }
        .subtitle { color: var(--muted); font-size: 14px; margin-bottom: 28px; }
        .section-title {
            font-size: 12px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase;
            color: var(--teal); margin: 28px 0 14px; padding-bottom: 6px;
            border-bottom: 1.5px solid #e5eaf0;
        }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        label { font-size: 13px; font-weight: 600; color: var(--navy); }
        input[type="text"], input[type="date"], input[type="number"], select {
            padding: 11px 14px; border: 1.5px solid #dce2ea; border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--navy);
            background: var(--white); outline: none; transition: border-color .2s, box-shadow .2s;
        }
        input:focus, select:focus {
            border-color: var(--teal); box-shadow: 0 0 0 3px rgba(13,115,119,.12);
        }
        .checkbox-group { display: flex; flex-direction: column; gap: 10px; margin-top: 4px; }
        .checkbox-item { display: flex; align-items: center; gap: 10px; font-size: 14px; cursor: pointer; }
        .checkbox-item input { width: 17px; height: 17px; accent-color: var(--teal); cursor: pointer; }
        .error-box {
            background: #fef2f2; border: 1.5px solid #fca5a5; color: #b91c1c;
            border-radius: var(--radius); padding: 12px 16px; margin-bottom: 20px; font-size: 14px;
        }
        .actions { display: flex; gap: 12px; margin-top: 32px; }
        .btn {
            padding: 13px 28px; border-radius: var(--radius); border: none;
            font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: background .2s, transform .1s; text-decoration: none;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-primary { background: var(--teal); color: var(--white); flex: 1; }
        .btn-primary:hover { background: #0a5c5f; }
        .btn-secondary { background: #e5eaf0; color: var(--navy); }
        .btn-secondary:hover { background: #d4dae4; }
        @media (max-width: 600px) { .grid, .grid-3 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="topbar">
    <div class="brand">🗺️ CensoApp</div>
    <a href="index.php?action=listar_censos">← Volver al listado</a>
</div>

<div class="container">
    <div class="card">
        <h2>Nuevo Registro de Censo</h2>
        <p class="subtitle">Complete todos los campos para registrar el censo del hogar.</p>

        <?php if (!empty($error)): ?>
            <div class="error-box">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="register_censo">

            <div class="section-title">Datos del Jefe/a de Hogar</div>
            <div class="grid">
                <div class="form-group">
                    <label for="nombre">Nombre completo *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: María García" required>
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha del censo *</label>
                    <input type="date" id="fecha" name="fecha" required value="<?= date('Y-m-d') ?>">
                </div>
            </div>

            <div class="section-title">Ubicación del Hogar</div>
            <div class="grid-3">
                <div class="form-group">
                    <label for="pais">País *</label>
                    <input type="text" id="pais" name="pais" placeholder="Colombia" required>
                </div>
                <div class="form-group">
                    <label for="departamento">Departamento *</label>
                    <input type="text" id="departamento" name="departamento" placeholder="Bolívar" required>
                </div>
                <div class="form-group">
                    <label for="ciudad">Ciudad *</label>
                    <input type="text" id="ciudad" name="ciudad" placeholder="Cartagena" required>
                </div>
            </div>
            <div class="form-group" style="margin-top:16px">
                <label for="casa">Dirección / Referencia *</label>
                <input type="text" id="casa" name="casa" placeholder="Ej: Calle 30 # 12-45, Barrio El Bosque" required>
            </div>

            <div class="section-title">Composición del Hogar</div>
            <div class="grid-3">
                <div class="form-group">
                    <label for="numHombres">Hombres adultos</label>
                    <input type="number" id="numHombres" name="numHombres" min="0" value="0">
                </div>
                <div class="form-group">
                    <label for="numMujeres">Mujeres adultas</label>
                    <input type="number" id="numMujeres" name="numMujeres" min="0" value="0">
                </div>
                <div class="form-group">
                    <label for="numNinos">Niños</label>
                    <input type="number" id="numNinos" name="numNinos" min="0" value="0">
                </div>
                <div class="form-group">
                    <label for="numNinas">Niñas</label>
                    <input type="number" id="numNinas" name="numNinas" min="0" value="0">
                </div>
                <div class="form-group">
                    <label for="numAncianosHombres">Ancianos (H)</label>
                    <input type="number" id="numAncianosHombres" name="numAncianosHombres" min="0" value="0">
                </div>
                <div class="form-group">
                    <label for="numAncianasMujeres">Ancianas (M)</label>
                    <input type="number" id="numAncianasMujeres" name="numAncianasMujeres" min="0" value="0">
                </div>
            </div>

            <div class="section-title">Condiciones de la Vivienda</div>
            <div class="grid">
                <div class="form-group">
                    <label for="numHabitaciones">N.º de habitaciones *</label>
                    <input type="number" id="numHabitaciones" name="numHabitaciones" min="1" value="1" required>
                </div>
                <div class="form-group">
                    <label for="numCamas">N.º de camas</label>
                    <input type="number" id="numCamas" name="numCamas" min="0" value="0">
                </div>
            </div>

            <div class="section-title">Servicios Públicos</div>
            <div class="checkbox-group">
                <label class="checkbox-item">
                    <input type="checkbox" name="tieneAgua" value="1"> Agua potable
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" name="tieneLuz" value="1"> Energía eléctrica
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" name="tieneAlcantarillado" value="1"> Alcantarillado
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" name="tieneGas" value="1"> Gas natural
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" name="tieneOtrosServicios" value="1"> Otros servicios (Internet, TV, etc.)
                </label>
            </div>

            <div class="section-title">Datos del Sensador</div>
            <div class="form-group">
                <label for="nombreSensador">Nombre del sensador *</label>
                <input type="text" id="nombreSensador" name="nombreSensador"
                    value="<?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?>"
                    placeholder="Nombre de quien realiza el censo" required>
            </div>

            <div class="actions">
                <a href="index.php?action=listar_censos" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Censo</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>