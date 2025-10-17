<?php
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';
require_once __DIR__ . '/../../config/database.php';

SessionHelper::start();
if (!SessionHelper::isLoggedIn()) {
    header("Location: ../../views/auth/login.php");
    exit();
}

$pacienteModel = new Paciente($conn);

// Acción según query param ?action=
$action = $_GET['action'] ?? 'listar';

switch($action) {
    case 'listar':
        $pacientes = $pacienteModel->getAll();
        include '../../views/pacientes/listar.php';
        break;

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pacienteModel->create($_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['email']);
            header('Location: PacienteController.php?action=listar');
            exit();
        }
        include '../../views/pacientes/crear.php';
        break;

    case 'editar':
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: PacienteController.php?action=listar'); exit(); }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pacienteModel->update($id, $_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['email']);
            header('Location: PacienteController.php?action=listar');
            exit();
        }

        $paciente = $pacienteModel->getById($id);
        include '../../views/pacientes/editar.php';
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? null;
        if ($id) $pacienteModel->delete($id);
        header('Location: PacienteController.php?action=listar');
        break;
}
?>
