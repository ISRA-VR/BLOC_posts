<?php
require __DIR__ . '/../layouts/header.php';
?>
<div class="row">
  <div class="col-12">
    <div class="card card-custom p-4">
      <h3 class="fw-bold mb-3">Posts</h3>
      <p class="text-muted">Administra los posts publicados.</p>
      <form class="row g-2 mb-3" method="get" action="index.php">
        <input type="hidden" name="controller" value="admin">
        <input type="hidden" name="action" value="posts">
        <div class="col-md-6">
          <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar por título, contenido o autor">
        </div>
        <div class="col-md-2">
          <select name="per" class="form-select">
            <?php $perSel = intval($pagination['per'] ?? ($_GET['per'] ?? 10));
            foreach ([10, 20, 30, 50] as $opt): ?>
              <option value="<?= $opt ?>" <?= $perSel === $opt ? 'selected' : '' ?>><?= $opt ?> / página</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-brand w-100"><i class="bi bi-search me-1"></i>Buscar</button>
        </div>
        <div class="col-md-2">
          <a class="btn btn-outline-secondary w-100" href="index.php?controller=admin&action=posts">Limpiar</a>
        </div>
      </form>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Título</th>
              <th>Autor</th>
              <th>Imagen</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($posts as $p): ?>
              <tr>
                <td><?= (int)$p['id'] ?></td>
                <td><?= htmlspecialchars($p['titulo']) ?></td>
                <td><?= htmlspecialchars($p['autor_nombre']) ?></td>
                <td>
                  <?php if (!empty($p['imagen'])): ?>
                    <a href="<?= htmlspecialchars($p['imagen']) ?>" target="_blank">Ver</a>
                  <?php else: ?>
                    <span class="text-muted">Sin imagen</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['fecha_creacion']) ?></td>
                <td class="d-flex gap-2">
                  <form method="post" action="index.php?controller=admin&action=deletepost" onsubmit="return confirm('¿Eliminar este post?');">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                  </form>
                  <a class="btn btn-sm btn-primary" href="index.php?controller=post&action=edit&id=<?= (int)$p['id'] ?>">Editar</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <nav class="d-flex justify-content-between align-items-center mt-3">
        <span class="text-muted">Total: <?= (int)($pagination['total'] ?? count($posts)) ?> posts</span>
        <?php $pages = (int)($pagination['pages'] ?? 1);
        $page = (int)($pagination['page'] ?? 1);
        $q = htmlspecialchars($_GET['q'] ?? '');
        $per = (int)($pagination['per'] ?? 10); ?>
        <ul class="pagination mb-0">
          <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="index.php?controller=admin&action=posts&q=<?= $q ?>&per=<?= $per ?>&page=<?= max(1, $page - 1) ?>">Anterior</a>
          </li>
          <?php for ($p = 1; $p <= $pages && $p <= 50; $p++): ?>
            <li class="page-item <?= $p === $page ? 'active' : '' ?>">
              <a class="page-link" href="index.php?controller=admin&action=posts&q=<?= $q ?>&per=<?= $per ?>&page=<?= $p ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
            <a class="page-link" href="index.php?controller=admin&action=posts&q=<?= $q ?>&per=<?= $per ?>&page=<?= min($pages, $page + 1) ?>">Siguiente</a>
          </li>
        </ul>
      </nav>
      <a class="btn btn-link mt-2" href="index.php?controller=admin&action=index">Volver al Dashboard</a>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>