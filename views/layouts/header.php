<?php
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog personal</title>

    <!-- Bootstrap 5 y Iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #ff0080;
            --bg-color: #f0f2f5;
            --text-color: #2d3748;
            --card-bg: #ffffff;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --radius-md: 12px;
            --radius-lg: 20px;
        }

        body {
            background: linear-gradient(135deg, #e1e8ffff 0%, #abe1ffff 50%, #97cbd5ff 100%);
            font-family: 'Outfit', sans-serif;
            color: var(--text-color);
            -webkit-font-smoothing: antialiased;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .navbar-custom {
            background: rgba(211, 234, 255, 1);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-sm);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .navbar-custom .navbar-brand {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            font-size: 1.5rem;
        }

        .navbar-custom .nav-link {
            color: #4a5568;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: var(--secondary-color);
        }

        /* Botones Premium */
        .btn-brand {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3);
        }

        .btn-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(118, 75, 162, 0.4);
            color: white;
        }

        .btn-outline-brand {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 50px;
            font-weight: 600;
            padding: 0.4rem 1.4rem;
            background: transparent;
            transition: all 0.3s;
        }

        .btn-outline-brand:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Cards */
        .card-custom {
            background: var(--card-bg);
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        /* Alertas */
        .alert {
            border-radius: var(--radius-md);
            border: none;
            box-shadow: var(--shadow-sm);
        }

        /* Modal Backdrop */
        .modal-overlay {
            backdrop-filter: blur(5px);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-light navbar-custom mb-5 py-3 sticky-top">
        <div class="container">

            <a class="navbar-brand" href="index.php?controller=posts&action=index">
                <i class="bi bi-journal-richtext me-2"></i>Mi Blog
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="ms-auto d-flex align-items-center gap-2">

                    <?php if (!empty($_SESSION['user'])): ?>
                        <div class="d-flex align-items-center me-3 text-muted">
                            <div class="bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span>Hola, <strong><?= htmlspecialchars($_SESSION['user']['nombre']) ?></strong></span>
                        </div>
                        <a class="btn btn-outline-danger btn-sm rounded-pill px-3" href="index.php?controller=auth&action=logout">
                            <i class="bi bi-box-arrow-right me-1"></i> Salir
                        </a>

                    <?php else: ?>
                        <a class="btn btn-link text-decoration-none text-secondary fw-bold" href="index.php?controller=auth&action=login">
                            Iniciar sesión
                        </a>
                        <a class="btn btn-brand shadow-sm" href="index.php?controller=auth&action=register">
                            Registrarse
                        </a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php
        // Mostrar mensajes flash con estilo
        if (!empty($_SESSION['flash'])) {
            echo '<div class="alert alert-warning border-0 shadow-sm text-center mb-4 fw-bold rounded-3">' . htmlspecialchars($_SESSION['flash']) . '</div>';
            unset($_SESSION['flash']);
        }
        ?>