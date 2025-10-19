<?php
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';
require_once __DIR__ . '/../../config/database.php';

SessionHelper::start();
if (!SessionHelper::isLoggedIn()) {
    header("Location: ../../views/auth/login.php");
    exit();
}

$citaModel = new Cita($conn);
$action = $_GET['action'] ?? 'listar';

switch ($action) {
    case 'listar':
        $citas = $citaModel->getAll();
        include '../../views/citas/listar.php';
        break;

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $citaModel->create(
                $_POST['paciente_id'],
                $_POST['medico_id'],
                $_POST['fecha'],
                $_POST['hora'],
                $_POST['motivo']
            );
            header('Location: CitaController.php?action=listar');
            exit();
        }
        include '../../views/citas/crear.php';
        break;

    case 'editar':
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: CitaController.php?action=listar'); exit(); }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $citaModel->update(
                $id,
                $_POST['paciente_id'],
                $_POST['medico_id'],
                $_POST['fecha'],
                $_POST['hora'],
                $_POST['motivo'],
                $_POST['estado']
            );
            header('Location: CitaController.php?action=listar');
            exit();
        }

        $cita = $citaModel->getById($id);
        include '../../views/citas/editar.php';
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? null;
        if ($id) $citaModel->delete($id);
        header('Location: CitaController.php?action=listar');
        break;
}
?>
