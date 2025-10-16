<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  header('Location: ../auth/login.php');
  exit;
}

require_once __DIR__ . '/../../layouts/header.php';
require_once __DIR__ . '/../../layouts/sidebar.php';
?>

<main style="margin-left:260px; padding:30px;">
  <h1 class="mb-4">Bienvenido, <?= htmlspecialchars($_SESSION['nombre_usuario']); ?> 👋</h1>

  <div class="row text-center mb-4">
    <div class="col-md-3">
      <div class="card p-3 shadow-sm">
        <h4>Pacientes</h4>
        <p class="display-6 text-primary fw-bold">124</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3 shadow-sm">
        <h4>Citas Hoy</h4>
        <p class="display-6 text-success fw-bold">18</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3 shadow-sm">
        <h4>Facturas</h4>
        <p class="display-6 text-warning fw-bold">42</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3 shadow-sm">
        <h4>Médicos Activos</h4>
        <p class="display-6 text-info fw-bold">15</p>
      </div>
    </div>
  </div>

  <div class="card p-4 shadow-sm">
    <h4>Gráfico de Citas por Mes</h4>
    <canvas id="chartCitas"></canvas>
  </div>
</main>

<script>
const ctx = document.getElementById('chartCitas');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
    datasets: [{
      label: 'Citas Atendidas',
      data: [12, 19, 8, 15, 10, 17],
      backgroundColor: '#2d9cff'
    }]
  },
  options: {
    scales: { y: { beginAtZero: true } }
  }
});
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
