<?php
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
?>

<main>
    <h2>Listado de Citas Médicas</h2>
    <a href="CitaController.php?action=crear">+ Nueva Cita</a>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Paciente</th>
            <th>Médico</th>
            <th>Especialidad</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($citas as $c): ?>
            <tr>
                <td><?= $c['cita_id'] ?></td>
                <td><?= $c['paciente'] ?></td>
                <td><?= $c['medico'] ?></td>
                <td><?= $c['especialidad'] ?></td>
                <td><?= $c['fecha'] ?></td>
                <td><?= $c['hora'] ?></td>
                <td><?= $c['estado'] ?></td>
                <td>
                    <a href="CitaController.php?action=editar&id=<?= $c['cita_id'] ?>">Editar</a> |
                    <a href="CitaController.php?action=eliminar&id=<?= $c['cita_id'] ?>" onclick="return confirm('¿Cancelar cita?')">Cancelar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php require_once '../layouts/footer.php'; ?>
