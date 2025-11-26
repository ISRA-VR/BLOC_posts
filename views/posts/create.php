<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="row">
  <div class="col-md-8">
    <h2>Crear Post</h2>
    <form method="post" action="index.php?controller=post&action=create">
      <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'] ?? '')?>">
      <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control" id="titulo" name="titulo" required>
      </div>
      <div class="mb-3">
        <label for="contenido" class="form-label">Contenido</label>
        <textarea class="form-control" id="contenido" name="contenido" rows="10" required></textarea>
      </div>
      <button class="btn btn-success">Guardar</button>
      <a class="btn btn-secondary" href="index.php?controller=posts&action=index">Cancelar</a>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>