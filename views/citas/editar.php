<?php
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../../config/database.php';

$pacientes = $conn->query("SELECT * FROM clinic.pacientes WHERE estado = 'Activo'")->fetchAll(PDO::FETCH_ASSOC);
$medicos = $conn->query("SELECT * FROM clinic.medicos WHERE estado = 'Activo'")->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <h2>Editar Cita</h2>
    <form method="POST" action="">
        <label>Paciente:</label>
        <select name="paciente_id">
            <?php foreach ($pacientes as $p): ?>
                <option value="<?= $p['paciente_id'] ?>" <?= $p['paciente_id'] == $cita['paciente_id'] ? 'selected' : '' ?>>
                    <?= $p['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Médico:</label>
        <select name="medico_id">
            <?php foreach ($medicos as $m): ?>
                <option value="<?= $m['medico_id'] ?>" <?= $m['medico_id'] == $cita['medico_id'] ? 'selected' : '' ?>>
                    <?= $m['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Fecha:</label><input type="date" name="fecha" value="<?= $cita['fecha'] ?>" required><br>
        <label>Hora:</label><input type="time" name="hora" value="<?= $cita['hora'] ?>" required><br>
        <label>Motivo:</label><textarea name="motivo" rows="3"><?= $cita['motivo'] ?></textarea><br>

        <label>Estado:</label>
        <select name="estado">
            <option value="Programada" <?= $cita['estado'] == 'Programada' ? 'selected' : '' ?>>Programada</option>
            <option value="Completada" <?= $cita['estado'] == 'Completada' ? 'selected' : '' ?>>Completada</option>
            <option value="Cancelada" <?= $cita['estado'] == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
        </select><br>

        <button type="submit">Actualizar</button>
    </form>
</main>

<?php require_once '../layouts/footer.php'; ?>
