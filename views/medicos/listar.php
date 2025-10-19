<?php
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
?>

<main>
    <h2>Listado de Médicos</h2>
    <a href="MedicoController.php?action=crear">+ Nuevo Médico</a>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Especialidad</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($medicos as $m): ?>
            <tr>
                <td><?= $m['medico_id'] ?></td>
                <td><?= $m['nombre'] ?></td>
                <td><?= $m['apellido'] ?></td>
                <td><?= $m['especialidad'] ?></td>
                <td><?= $m['telefono'] ?></td>
                <td><?= $m['email'] ?></td>
                <td>
                    <a href="MedicoController.php?action=editar&id=<?= $m['medico_id'] ?>">Editar</a> |
                    <a href="MedicoController.php?action=eliminar&id=<?= $m['medico_id'] ?>" onclick="return confirm('¿Eliminar médico?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php require_once '../layouts/footer.php'; ?>
