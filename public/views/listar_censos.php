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
            padding: 0 32px;
            height: 60px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-left {
            display: flex; align-items: center; gap: 16px;
        }
        .brand {
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .org-badge {
            display: flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 13px;
            color: var(--gold);
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .org-badge span.dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--gold);
            display: inline-block;
        }
        .topbar-right {
            display: flex; align-items: center; gap: 20px; font-size: 13px;
        }
        .user-info {
            display: flex; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.7);
        }
        .role-tag {
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
            padding: 2px 7px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255,255,255,0.55);
            letter-spacing: 0.5px;
        }
        .topbar-right a {
            color: var(--gold); text-decoration: none; font-weight: 600;
            transition: opacity .2s;
        }
        .topbar-right a:hover { opacity: .8; }
        .topbar-right a.logout {
            color: rgba(255,255,255,0.5);
            font-weight: 400;
        }
        .topbar-right a.logout:hover { color: var(--white); }

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
    <div class="topbar-left">
        <div class="brand">🗺️ CensoApp</div>

        <?php if (!empty($_SESSION['organizacion_nombre'])): ?>
            <div class="org-badge">
                <span class="dot"></span>
                <?= htmlspecialchars($_SESSION['organizacion_nombre']) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="topbar-right">
        <?php if (!empty($_SESSION['usuario_nombre'])): ?>
            <div class="user-info">
                👤 <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
                <span class="role-tag"><?= htmlspecialchars($_SESSION['usuario_rol'] ?? '') ?></span>
            </div>
        <?php endif; ?>

        <?php if (($_SESSION['usuario_rol'] ?? '') === 'admin'): ?>
            <a href="index.php?action=listar_usuarios">👥 Usuarios</a>
        <?php endif; ?>

        <a href="login.html" class="logout">Cerrar sesión</a>
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
                                <a href="index.php?action=mostrar_registro"
                                style="color:var(--teal);font-weight:600">
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