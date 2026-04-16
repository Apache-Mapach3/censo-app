<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario — CensoApp</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --navy:#0f1f3d; --teal:#0d7377; --gold:#e8a020; --light:#f4f6f9; --muted:#8896a8; --white:#ffffff; --radius:10px; }
        body { font-family: 'DM Sans', sans-serif; background: var(--light); color: var(--navy); }
        .topbar { background: var(--navy); color: var(--white); padding: 14px 32px; display: flex; align-items: center; justify-content: space-between; }
        .topbar .brand { font-family: 'DM Serif Display', serif; font-size: 20px; }
        .topbar a { color: var(--gold); text-decoration: none; font-size: 13px; font-weight: 600; }
        .container { max-width: 560px; margin: 36px auto; padding: 0 16px; }
        .card { background: var(--white); border-radius: 14px; padding: 36px; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
        h2 { font-family: 'DM Serif Display', serif; font-size: 26px; margin-bottom: 4px; }
        .subtitle { color: var(--muted); font-size: 14px; margin-bottom: 28px; }
        .badge { display: inline-block; background: #dbeafe; color: #1e40af; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px; margin-left: 10px; vertical-align: middle; }
        .section-title { font-size: 12px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--teal); margin: 24px 0 14px; padding-bottom: 6px; border-bottom: 1.5px solid #e5eaf0; }
        .form-group { margin-bottom: 18px; display: flex; flex-direction: column; gap: 6px; }
        label { font-size: 13px; font-weight: 600; color: var(--navy); }
        input[type="text"], input[type="password"], input[type="email"], select {
            width: 100%; padding: 11px 14px; border: 1.5px solid #dce2ea; border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--navy);
            background: var(--white); outline: none; transition: border-color .2s, box-shadow .2s;
        }
        input:focus, select:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(13,115,119,.12); }
        .hint { font-size: 12px; color: var(--muted); margin-top: 2px; }
        .rol-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .rol-option { border: 1.5px solid #dce2ea; border-radius: var(--radius); padding: 14px; cursor: pointer; transition: border-color .2s, background .2s; position: relative; }
        .rol-option input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .rol-option:has(input:checked) { border-color: var(--teal); background: rgba(13,115,119,.05); }
        .rol-icon { font-size: 22px; margin-bottom: 6px; }
        .rol-title { font-size: 13px; font-weight: 600; color: var(--navy); }
        .actions { display: flex; gap: 12px; margin-top: 28px; }
        .btn { padding: 13px 28px; border-radius: var(--radius); border: none; font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 600; cursor: pointer; transition: background .2s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
        .btn-primary { background: var(--teal); color: var(--white); flex: 1; }
        .btn-primary:hover { background: #0a5c5f; }
        .btn-secondary { background: #e5eaf0; color: var(--navy); }
        .btn-secondary:hover { background: #d4dae4; }
    </style>
</head>
<body>
<div class="topbar">
    <div class="brand">🗺️ CensoApp</div>
    <a href="index.php?action=listar_usuarios">← Volver al listado</a>
</div>

<div class="container">
    <div class="card">
        <h2>Editar Usuario <span class="badge">#<?= $usuario->getId() ?></span></h2>
        <p class="subtitle">Modifica los datos del usuario. Deja la contraseña en blanco para no cambiarla.</p>

        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="actualizar_usuario">
            <input type="hidden" name="id" value="<?= $usuario->getId() ?>">

            <div class="section-title">Datos de Acceso</div>
            <div class="form-group">
                <label for="nombre">Nombre de usuario *</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario->getNombre()) ?>" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($usuario->getCorreo()) ?>" placeholder="ejemplo@correo.com">
            </div>
            <div class="form-group">
                <label for="nueva_clave">Nueva contraseña</label>
                <input type="password" id="nueva_clave" name="nueva_clave" placeholder="Dejar en blanco para no cambiar">
                <span class="hint">Mínimo 6 caracteres si deseas cambiarla.</span>
            </div>

            <div class="section-title">Rol en el sistema</div>
            <div class="rol-grid">
                <label class="rol-option">
                    <input type="radio" name="rol" value="admin" <?= $usuario->getRol() === 'admin' ? 'checked' : '' ?> required>
                    <div class="rol-icon">🛡️</div>
                    <div class="rol-title">Administrador</div>
                </label>
                <label class="rol-option">
                    <input type="radio" name="rol" value="usuario" <?= $usuario->getRol() === 'usuario' ? 'checked' : '' ?>>
                    <div class="rol-icon">📋</div>
                    <div class="rol-title">Sensador</div>
                </label>
            </div>

            <div class="actions">
                <a href="index.php?action=listar_usuarios" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>