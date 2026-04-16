<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Censos Registrados — CensoApp</title>
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
            --red:    #dc3545;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--light); }
        .topbar {
            background: var(--navy); color: var(--white);
            padding: 14px 32px; display: flex; align-items: center; justify-content: space-between;
        }
        .topbar .brand { font-family: 'DM Serif Display', serif; font-size: 20px; display: flex; align-items: center; gap: 10px; }
        .topbar-right { display: flex; align-items: center; gap: 20px; font-size: 13px; }
        .topbar-right span { color: rgba(255,255,255,.55); }
        .topbar-right a { color: var(--gold); text-decoration: none; font-weight: 600; }
        .container { max-width: 1100px; margin: 32px auto; padding: 0 20px; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .header h2 { font-family: 'DM Serif Display', serif; font-size: 26px; color: var(--navy); }
        .btn-new {
            background: var(--teal); color: var(--white); padding: 10px 20px;
            border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;
            transition: background .2s;
        }
        .btn-new:hover { background: #0a5c5f; }
        .card { background: var(--white); border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.07); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: var(--navy); color: var(--white);
            padding: 13px 16px; text-align: left; font-size: 13px; font-weight: 600;
            letter-spacing: .4px;
        }
        tbody td { padding: 13px 16px; font-size: 14px; color: var(--navy); border-bottom: 1px solid #f0f2f5; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #f8fafc; }
        .badge-total {
            background: #dbeafe; color: #1e40af;
            font-size: 12px; font-weight: 700; padding: 2px 10px; border-radius: 20px;
        }
        .btn-edit {
            background: var(--gold); color: var(--white); padding: 6px 14px;
            border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600;
            transition: background .2s; white-space: nowrap;
        }
        .btn-edit:hover { background: #c88a1a; }
        /* BUG CORREGIDO: el botón eliminar ahora está dentro de un form
           que envía el id por POST y el action apunta correctamente */
        .btn-delete {
            background: var(--red); color: var(--white); padding: 6px 14px;
            border-radius: 6px; border: none; font-family: 'DM Sans', sans-serif;
            font-size: 13px; font-weight: 600; cursor: pointer;
            transition: background .2s; white-space: nowrap;
        }
        .btn-delete:hover { background: #b02a37; }
        .actions-cell { display: flex; gap: 8px; flex-wrap: wrap; }
        .empty { text-align: center; padding: 48px; color: var(--muted); font-style: italic; }
        .empty-icon { font-size: 40px; margin-bottom: 12px; }
    </style>
</head>
<body>

<div class="topbar">
    <div class="brand">🗺️ CensoApp</div>
    <div class="topbar-right" style="display: flex; gap: 20px; align-items: center;">
        
        <?php if (!empty($_SESSION['usuario_nombre'])): ?>
            <span>👤 <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
                (<?= htmlspecialchars($_SESSION['usuario_rol'] ?? '') ?>)</span>
        <?php endif; ?>

        <?php if (($_SESSION['usuario_rol'] ?? '') === 'admin'): ?>
            <a href="index.php?action=listar_usuarios" style="color: #e8a020; text-decoration: none; font-weight: 600;">
                👥 Gestionar Usuarios
            </a>
        <?php endif; ?>

        <a href="login.html" style="color: white; text-decoration: none;">Cerrar sesión</a>
        
    </div>
</div>

<div class="container">
    <div class="header">
        <h2>Censos Registrados</h2>
        <a href="index.php?action=mostrar_registro" class="btn-new">+ Nuevo Censo</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jefe/a de Hogar</th>
                    <th>Fecha</th>
                    <th>Departamento</th>
                    <th>Ciudad</th>
                    <th>Habitantes</th>
                    <th>Sensador</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($censos)): ?>
                    <?php foreach ($censos as $censo): ?>
                        <tr>
                            <td><?= $censo->getId() ?></td>
                            <td><?= htmlspecialchars($censo->getNombre()) ?></td>
                            <td><?= $censo->getFecha()->format('d/m/Y') ?></td>
                            <td><?= htmlspecialchars($censo->getDepartamento()) ?></td>
                            <td><?= htmlspecialchars($censo->getCiudad()) ?></td>
                            <td>
                                <span class="badge-total">
                                    <?= $censo->getNumHombres()
                                        + $censo->getNumMujeres()
                                        + $censo->getNumAncianosHombres()
                                        + $censo->getNumAncianasMujeres()
                                        + $censo->getNumNinos()
                                        + $censo->getNumNinas() ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($censo->getNombreSensador()) ?></td>
                            <td>
                                <div class="actions-cell">
                                    <a href="index.php?action=editar_censo&id=<?= $censo->getId() ?>"
                                    class="btn-edit">Editar</a>

                                    <form action="index.php" method="POST" style="display:inline"
                                        onsubmit="return confirm('¿Eliminar este censo de forma permanente?')">
                                        <input type="hidden" name="action" value="eliminar_censo">
                                        <input type="hidden" name="id" value="<?= $censo->getId() ?>">
                                        <button type="submit" class="btn-delete">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty">
                                <div class="empty-icon">📋</div>
                                No hay censos registrados todavía.<br>
                                <a href="index.php?action=mostrar_registro" style="color:var(--teal);font-weight:600">
                                    Registra el primero
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>