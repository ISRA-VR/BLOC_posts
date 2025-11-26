<?php
// views/layouts/header.php
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

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .navbar-custom .text-primary {
            color: #764ba2 !important; 
        }

        .btn-brand {
            background-color: #764ba2;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-brand:hover {
            background-color: #5a367e;
            transform: translateY(-2px); /* Efecto de elevación */
            color: white;
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
        }

        .card-custom {
            border-top: 5px solid #764ba2;
        }
        
        /* 5. Alerta personalizada */
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffecb5;
            color: #664d03;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100"> 

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4 py-3">
  <div class="container">
    
    <a class="navbar-brand fw-bold" href="index.php?controller=posts&action=index">
        <i class="bi bi-journal-text me-2"></i>Mi Blog
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <div class="ms-auto d-flex align-items-center">
        
        <?php if (!empty($_SESSION['user'])): ?>
            <span class="navbar-text text-white me-3">
                Hola, <strong><?=htmlspecialchars($_SESSION['user']['nombre'])?></strong>
            </span>
            <a class="btn btn-sm btn-light text-primary fw-bold shadow-sm" href="index.php?controller=auth&action=logout">
                <i class="bi bi-box-arrow-right me-1"></i> Salir
            </a>

        <?php else: ?>
            <a class="btn btn-sm btn-outline-light me-2" href="index.php?controller=auth&action=login">
                Entrar
            </a>
            <a class="btn btn-sm btn-light text-primary fw-bold shadow-sm" href="index.php?controller=auth&action=register">
                Registrar
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