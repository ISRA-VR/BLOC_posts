<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Posts</h2>
  <div>
    <a class="btn btn-primary" href="index.php?controller=post&action=create">Crear post</a>
  </div>
</div>

<?php if (empty($posts)): ?>
  <div class="alert alert-warning">No hay posts todavía.</div>
<?php else: ?>
  <div class="list-group">
    <?php foreach ($posts as $p): ?>
      <div class="list-group-item mb-2 shadow-sm">
        <div class="d-flex justify-content-between">
          <h5><?=htmlspecialchars($p['titulo'])?></h5>
          <small class="text-muted"><?=htmlspecialchars($p['autor_nombre'])?> • <?=htmlspecialchars($p['fecha_creacion'])?></small>
        </div>
        <p class="mt-2"><?=nl2br(htmlspecialchars(strlen($p['contenido']) > 300 ? substr($p['contenido'], 0, 300) . '...' : $p['contenido']))?></p>
        <div class="mt-2 d-flex gap-2">
          <a class="btn btn-sm btn-outline-secondary" href="index.php?controller=post&action=edit&id=<?=$p['id']?>">Editar</a>

          <!-- Form para eliminar (POST con CSRF) -->
          <form method="post" action="index.php?controller=post&action=delete" onsubmit="return confirm('¿Estás seguro que quieres eliminar este post?');" style="display:inline;">
            <input type="hidden" name="id" value="<?=$p['id']?>">
            <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'] ?? '')?>">
            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>