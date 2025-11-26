<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Registro</h2>
    <form method="post" action="index.php?controller=auth&action=register" novalidate>
      <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'] ?? '')?>">
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Correo</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="mb-3">
        <label for="password2" class="form-label">Repetir contraseña</label>
        <input type="password" class="form-control" id="password2" name="password2" required>
      </div>
      <button class="btn btn-success">Registrarse</button>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>