<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios — CensoApp</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #f4f6f9; margin: 0; padding: 40px; color: #0f1f3d; }
        .container { max-width: 900px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        h2 { font-family: 'DM Serif Display', serif; font-size: 28px; margin: 0; color: #0f1f3d; }
        .btn-back { background: #0f1f3d; color: #fff; text-decoration: none; padding: 10px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; }
        .btn-back:hover { background: #0d7377; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid #dce2ea; font-size: 14px; }
        th { background: #f8fafc; color: #8896a8; text-transform: uppercase; letter-spacing: 1px; font-size: 12px; font-weight: 600; border-radius: 4px; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-admin { background: #fee2e2; color: #b91c1c; }
        .badge-user { background: #d1fae5; color: #065f46; }
        .actions a { text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; margin-right: 5px; }
        .btn-edit { background: #e8a020; color: white; }
        .btn-delete { background: #ef4444; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>👥 Gestión de Usuarios</h2>
        <a href="index.php?action=listar_censos" class="btn-back">⬅ Volver al Inicio</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u->getId() ?? '') ?></td>
                    <td><strong><?= htmlspecialchars($u->getNombre() ?? '') ?></strong></td>
                    <td><?= htmlspecialchars(method_exists($u, 'getCorreo') ? $u->getCorreo() : 'Sin correo') ?></td>
                    <td>
                        <?php $rol = $u->getRol() ?? 'comun'; ?>
                        <span class="badge <?= $rol === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                            <?= htmlspecialchars(strtoupper($rol)) ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="index.php?action=editar_usuario&id=<?= $u->getId() ?>" class="btn-edit">Editar</a>
                        <form action="index.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                            <input type="hidden" name="action" value="eliminar_usuario">
                            <input type="hidden" name="id" value="<?= $u->getId() ?>">
                            <button type="submit" class="btn-delete" style="border:none; cursor:pointer;">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; padding: 20px;">No hay usuarios registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>