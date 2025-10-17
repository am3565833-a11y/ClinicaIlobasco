<?php
require_once __DIR__ . '/../../config/app_config.php';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="dashboard">
    <h1>Bienvenido a <?= APP_NAME ?> 🏥</h1>
    <p>Has iniciado sesión correctamente como <strong><?= htmlspecialchars($_SESSION['usuario'] ?? 'Invitado') ?></strong>.</p>

    <div class="cards">
        <div class="card">
            <h3>Pacientes</h3>
            <p>Administrar registros de pacientes.</p>
            <a href="<?= BASE_URL ?>views/pacientes/listar.php" class="btn">Ir</a>
        </div>
        <div class="card">
            <h3>Citas</h3>
            <p>Gestionar las citas médicas.</p>
            <a href="<?= BASE_URL ?>views/citas/listar.php" class="btn">Ir</a>
        </div>
        <div class="card">
            <h3>Facturación</h3>
            <p>Ver y registrar pagos o facturas.</p>
            <a href="<?= BASE_URL ?>views/facturacion/listar.php" class="btn">Ir</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
