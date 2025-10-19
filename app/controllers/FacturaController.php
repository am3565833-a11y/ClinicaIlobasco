<?php
require_once __DIR__ . '/../models/Factura.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';
require_once __DIR__ . '/../../config/database.php';

SessionHelper::start();
if (!SessionHelper::isLoggedIn()) {
    header("Location: ../../views/auth/login.php");
    exit();
}

$facturaModel = new Factura($conn);
$action = $_GET['action'] ?? 'listar';

switch ($action) {
    case 'listar':
        $facturas = $facturaModel->getAll();
        include '../../views/facturacion/listar.php';
        break;

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $facturaModel->create(
                $_POST['paciente_id'],
                $_POST['detalle'],
                $_POST['total']
            );
            header('Location: FacturaController.php?action=listar');
            exit();
        }
        include '../../views/facturacion/crear.php';
        break;

    case 'ver':
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: FacturaController.php?action=listar'); exit(); }

        $factura = $facturaModel->getById($id);
        include '../../views/facturacion/ver.php';
        break;

    case 'cambiarEstado':
        $id = $_GET['id'] ?? null;
        $estado = $_GET['estado'] ?? 'Pagada';
        if ($id) $facturaModel->updateEstado($id, $estado);
        header('Location: FacturaController.php?action=listar');
        break;
}
?>
