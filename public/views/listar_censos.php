<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Censos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f6f9; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #0f1f3d; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .header-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 8px 12px; text-decoration: none; border-radius: 4px; color: white; font-weight: bold; border: none; cursor: pointer; }
        .btn-new { background-color: #0d7377; }
        .btn-new:hover { background-color: #14a085; }
        .btn-edit { background-color: #e8a020; font-size: 0.9em; }
        .btn-delete { background-color: #dc3545; font-size: 0.9em; margin-left: 5px; }
        .empty-message { text-align: center; color: #666; font-style: italic; padding: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-container">
        <h2>Censos Registrados</h2>
        <a href="index.php?action=mostrar_registro" class="btn btn-new">+ Nuevo Censo</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Jefe/a de Hogar</th>
                <th>Fecha</th>
                <th>Departamento</th>
                <th>Ciudad</th>
                <th>Total Habitantes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($censos)): ?>
                <?php foreach ($censos as $censo): ?>
                    <tr>
                        <td><?= $censo->getId(); ?></td>
                        <td><?= htmlspecialchars($censo->getNombre()); ?></td>
                        <td><?= $censo->getFecha()->format('d/m/Y'); ?></td>
                        <td><?= htmlspecialchars($censo->getDepartamento()); ?></td>
                        <td><?= htmlspecialchars($censo->getCiudad()); ?></td>
                        <td><?= $censo->getNumHombres() + $censo->getNumMujeres(); ?></td>
                        <td>
                            <a href="index.php?action=editar_censo&id=<?= $censo->getId(); ?>" class="btn btn-edit">Editar</a>
                            <form action="index.php?action=eliminar_censo" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $censo->getId(); ?>">
                                <button type="submit" class="btn btn-delete" onclick="return confirm('¿Eliminar este censo de forma permanente?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="empty-message">No hay censos registrados en este momento.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>