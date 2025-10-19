<?php
require_once __DIR__ . '/../models/Medico.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';
require_once __DIR__ . '/../../config/database.php';

SessionHelper::start();
if (!SessionHelper::isLoggedIn()) {
    header("Location: ../../views/auth/login.php");
    exit();
}

$medicoModel = new Medico($conn);
$action = $_GET['action'] ?? 'listar';

switch ($action) {
    case 'listar':
        $medicos = $medicoModel->getAll();
        include '../../views/medicos/listar.php';
        break;

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medicoModel->create(
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['telefono'],
                $_POST['email'],
                $_POST['especialidad_id'],
                $_POST['jornada_id']
            );
            header('Location: MedicoController.php?action=listar');
            exit();
        }
        include '../../views/medicos/crear.php';
        break;

    case 'editar':
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: MedicoController.php?action=listar'); exit(); }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medicoModel->update(
                $id,
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['telefono'],
                $_POST['email'],
                $_POST['especialidad_id'],
                $_POST['jornada_id']
            );
            header('Location: MedicoController.php?action=listar');
            exit();
        }

        $medico = $medicoModel->getById($id);
        include '../../views/medicos/editar.php';
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? null;
        if ($id) $medicoModel->delete($id);
        header('Location: MedicoController.php?action=listar');
        break;
}
?>
