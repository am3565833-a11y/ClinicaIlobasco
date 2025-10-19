<?php
require_once '../layouts/header.php';
require_once '../layouts/sidebar.php';
require_once '../../config/database.php';

// Cargar listas
$especialidades = $conn->query("SELECT * FROM clinic.especialidades")->fetchAll(PDO::FETCH_ASSOC);
$jornadas = $conn->query("SELECT * FROM clinic.jornadas")->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <h2>Editar Médico</h2>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $medico['nombre'] ?>" required><br>

        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?= $medico['apellido'] ?>" required><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= $medico['telefono'] ?>"><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= $medico['email'] ?>"><br>

        <label>Especialidad:</label>
        <select name="especialidad_id">
            <?php foreach ($especialidades as $e): ?>
                <option value="<?= $e['especialidad_id'] ?>" <?= $e['especialidad_id'] == $medico['especialidad_id'] ? 'selected' : '' ?>>
                    <?= $e['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Jornada:</label>
        <select name="jornada_id">
            <?php foreach ($jornadas as $j): ?>
                <option value="<?= $j['jornada_id'] ?>" <?= $j['jornada_id'] == $medico['jornada_id'] ? 'selected' : '' ?>>
                    <?= $j['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Actualizar</button>
    </form>
</main>

<?php require_once '../layouts/footer.php'; ?>
