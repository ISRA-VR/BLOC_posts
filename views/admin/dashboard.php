<?php
require __DIR__ . '/../layouts/header.php';
?>
<div class="row">
  <div class="col-12">
    <div class="card card-custom p-4">
      <h3 class="fw-bold mb-3">Panel de Administración</h3>
      <p class="text-muted">Resumen general del sistema.</p>
      <div class="row g-3">
        <div class="col-md-4">
          <div class="alert alert-primary">
            <div class="fw-bold">Usuarios</div>
            <div class="fs-3"><?= (int)$stats['usuarios'] ?></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="alert alert-warning">
            <div class="fw-bold">Usuarios suspendidos</div>
            <div class="fs-3"><?= (int)$stats['usuarios_suspendidos'] ?></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="alert alert-success">
            <div class="fw-bold">Posts</div>
            <div class="fs-3"><?= (int)$stats['posts'] ?></div>
          </div>
        </div>
      </div>
      <div class="d-flex gap-2 mt-3">
        <a class="btn btn-brand" href="index.php?controller=admin&action=users"><i class="bi bi-people me-1"></i>Gestionar Usuarios</a>
        <a class="btn btn-outline-brand" href="index.php?controller=admin&action=posts"><i class="bi bi-journal-text me-1"></i>Gestionar Posts</a>
      </div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>