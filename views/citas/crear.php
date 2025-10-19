<?php
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../../config/database.php';

$pacientes = $conn->query("SELECT * FROM clinic.pacientes WHERE estado = 'Activo'")->fetchAll(PDO::FETCH_ASSOC);
$medicos = $conn->query("SELECT * FROM clinic.medicos WHERE estado = 'Activo'")->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <h2>Registrar Nueva Cita</h2>
    <form method="POST" action="">
        <label>Paciente:</label>
        <select name="paciente_id" required>
            <option value="">Seleccione</option>
            <?php foreach ($pacientes as $p): ?>
                <option value="<?= $p['paciente_id'] ?>"><?= $p['nombre'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Médico:</label>
        <select name="medico_id" required>
            <option value="">Seleccione</option>
            <?php foreach ($medicos as $m): ?>
                <option value="<?= $m['medico_id'] ?>"><?= $m['nombre'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Fecha:</label><input type="date" name="fecha" required><br>
        <label>Hora:</label><input type="time" name="hora" required><br>
        <label>Motivo:</label><textarea name="motivo" rows="3"></textarea><br>

        <button type="submit">Guardar Cita</button>
    </form>
</main>

<?php require_once '../layouts/footer.php'; ?>
