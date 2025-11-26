
<?php
// header.php incluido en vistas
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog MVC</title>
    <!-- Bootstrap (opcional, puedes usar solo CSS3) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php?controller=posts&action=index">Mi Blog</a>
    <div>
      <?php if (!empty($_SESSION['user'])): ?>
        <span class="navbar-text text-light me-2">Hola, <?=htmlspecialchars($_SESSION['user']['nombre'])?></span>
        <a class="btn btn-sm btn-outline-light" href="index.php?controller=auth&action=logout">Salir</a>
      <?php else: ?>
        <a class="btn btn-sm btn-outline-light me-2" href="index.php?controller=auth&action=login">Entrar</a>
        <a class="btn btn-sm btn-outline-light" href="index.php?controller=auth&action=register">Registrar</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container">
<?php
// Mostrar mensajes flash simples
if (!empty($_SESSION['flash'])) {
    echo '<div class="alert alert-info">'.htmlspecialchars($_SESSION['flash']).'</div>';
    unset($_SESSION['flash']);
}
?>