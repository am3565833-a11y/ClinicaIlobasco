<?php
require_once __DIR__ . '/../../app/helpers/SessionHelper.php';
SessionHelper::start();
$nombre = $_SESSION['nombre_usuario'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal - Clínica Interna</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #0078D7;
            color: white;
            padding: 15px 30px;
            text-align: center;
        }
        .container {
            max-width: 1100px;
            margin: 30px auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h1 {
            margin-bottom: 10px;
            color: #333;
        }
        .modules {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h2 {
            color: #0078D7;
        }
        .card p {
            color: #555;
            font-size: 14px;
        }
        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background: #0078D7;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }
        .card a:hover {
            background: #005ea6;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido a Clínica Interna 🏥</h1>
        <p>Has iniciado sesión correctamente como <strong><?php echo htmlspecialchars($nombre); ?></strong>.</p>
    </header>

    <div class="container">
        <div class="modules">
            <div class="card">
                <h2>Pacientes</h2>
                <p>Administrar registros de pacientes.</p>
                <a href="../pacientes/listar.php">Entrar</a>
            </div>

            <div class="card">
                <h2>Citas</h2>
                <p>Gestionar las citas médicas.</p>
                <a href="views/citas/listar.php">Entrar</a>
            </div>

            <div class="card">
                <h2>Facturación</h2>
                <p>Ver y registrar pagos o facturas.</p>
                <a href="views/medicos/listar.php">Entrar</a>
            </div>
        </div>
    </div>
</body>
</html>
