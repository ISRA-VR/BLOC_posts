<?php
require __DIR__ . '/../layouts/header.php';
?>
<div class="row">
  <div class="col-12">
    <div class="card card-custom p-4">
      <h3 class="fw-bold mb-3">Usuarios</h3>
      <p class="text-muted">Administra roles, suspensiones y eliminación.</p>
      <form class="row g-2 mb-3" method="get" action="index.php">
        <input type="hidden" name="controller" value="admin">
        <input type="hidden" name="action" value="users">
        <div class="col-md-6">
          <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control" placeholder="Buscar por nombre o email">
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
          <a class="btn btn-outline-secondary w-100" href="index.php?controller=admin&action=users">Limpiar</a>
        </div>
      </form>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Rol</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td><?= (int)$u['id'] ?></td>
                <td><?= htmlspecialchars($u['nombre']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                  <form method="post" action="index.php?controller=admin&action=setrole" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                    <select name="rol" class="form-select form-select-sm" style="max-width: 140px;">
                      <option value="autor" <?= $u['rol'] === 'autor' ? 'selected' : '' ?>>Autor</option>
                      <option value="admin" <?= $u['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <button class="btn btn-sm btn-primary">Actualizar</button>
                  </form>
                </td>
                <td>
                  <?php if ((int)$u['suspendido'] === 1): ?>
                    <span class="badge bg-warning text-dark">Suspendido</span>
                  <?php else: ?>
                    <span class="badge bg-success">Activo</span>
                  <?php endif; ?>
                </td>
                <td class="d-flex gap-2">
                  <?php $isAdminUser = ($u['rol'] === 'admin');
                  $isSelf = (isset($_SESSION['user']['id']) && (int)$u['id'] === (int)$_SESSION['user']['id']); ?>
                  <?php if (!$isAdminUser && !$isSelf): ?>
                    <form method="post" action="index.php?controller=admin&action=suspend">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                      <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                      <input type="hidden" name="suspend" value="<?= (int)$u['suspendido'] ? 0 : 1 ?>">
                      <button class="btn btn-sm <?= (int)$u['suspendido'] ? 'btn-success' : 'btn-warning' ?>">
                        <?= (int)$u['suspendido'] ? 'Reactivar' : 'Suspender' ?>
                      </button>
                    </form>
                    <form method="post" action="index.php?controller=admin&action=deleteuser" onsubmit="return confirm('¿Eliminar usuario y sus posts?');">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                      <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                      <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                  <?php else: ?>
                    <span class="text-muted">Acción no disponible para administradores o tu propia cuenta.</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <nav class="d-flex justify-content-between align-items-center mt-3">
        <span class="text-muted">Total: <?= (int)($pagination['total'] ?? count($users)) ?> usuarios</span>
        <?php $pages = (int)($pagination['pages'] ?? 1);
        $page = (int)($pagination['page'] ?? 1);
        $q = htmlspecialchars($_GET['q'] ?? '');
        $per = (int)($pagination['per'] ?? 10); ?>
        <ul class="pagination mb-0">
          <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="index.php?controller=admin&action=users&q=<?= $q ?>&per=<?= $per ?>&page=<?= max(1, $page - 1) ?>">Anterior</a>
          </li>
          <?php for ($p = 1; $p <= $pages && $p <= 50; $p++): ?>
            <li class="page-item <?= $p === $page ? 'active' : '' ?>">
              <a class="page-link" href="index.php?controller=admin&action=users&q=<?= $q ?>&per=<?= $per ?>&page=<?= $p ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= $page >= $pages ? 'disabled' : '' ?>">
            <a class="page-link" href="index.php?controller=admin&action=users&q=<?= $q ?>&per=<?= $per ?>&page=<?= min($pages, $page + 1) ?>">Siguiente</a>
          </li>
        </ul>
      </nav>
      <a class="btn btn-link mt-2" href="index.php?controller=admin&action=index">Volver al Dashboard</a>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>