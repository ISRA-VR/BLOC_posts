<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Iniciar sesión</h2>
    <form method="post" action="index.php?controller=auth&action=login" novalidate>
      <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'] ?? '')?>">
      <div class="mb-3">
        <label for="email" class="form-label">Correo</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button class="btn btn-primary">Entrar</button>
    </form>
    <p class="mt-3">¿No tienes cuenta? <a href="index.php?controller=auth&action=register">Regístrate</a></p>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>