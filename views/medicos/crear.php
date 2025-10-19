<?php
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../../config/database.php';

// Obtener listas de especialidades y jornadas
$especialidades = $conn->query("SELECT * FROM clinic.especialidades")->fetchAll(PDO::FETCH_ASSOC);
$jornadas = $conn->query("SELECT * FROM clinic.jornadas")->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <h2>Registrar Médico</h2>
    <form method="POST" action="">
        <label>Nombre:</label><input type="text" name="nombre" required><br>
        <label>Apellido:</label><input type="text" name="apellido" required><br>
        <label>Teléfono:</label><input type="text" name="telefono"><br>
        <label>Email:</label><input type="email" name="email"><br>

        <label>Especialidad:</label>
        <select name="especialidad_id" required>
            <option value="">Seleccione</option>
            <?php foreach ($especialidades as $e): ?>
                <option value="<?= $e['especialidad_id'] ?>"><?= $e['nombre'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Jornada:</label>
        <select name="jornada_id" required>
            <option value="">Seleccione</option>
            <?php foreach ($jornadas as $j): ?>
                <option value="<?= $j['jornada_id'] ?>"><?= $j['nombre'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Guardar</button>
    </form>
</main>

<?php require_once '../layouts/footer.php'; ?>
