<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="row">
  <div class="col-md-8">
    <h2>Editar Post</h2>
    <form method="post" action="index.php?controller=post&action=edit&id=<?=$post['id']?>">
      <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'] ?? '')?>">
      <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control" id="titulo" name="titulo" required value="<?=htmlspecialchars($post['titulo'])?>">
      </div>
      <div class="mb-3">
        <label for="contenido" class="form-label">Contenido</label>
        <textarea class="form-control" id="contenido" name="contenido" rows="10" required><?=htmlspecialchars($post['contenido'])?></textarea>
      </div>
      <button class="btn btn-primary">Actualizar</button>
      <a class="btn btn-secondary" href="index.php?controller=posts&action=index">Volver</a>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>