<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
  <div class="col-md-6 col-lg-5">
    
    <div class="card border-0 shadow-lg rounded-4">
      <div class="card-body p-5">
        
        <div class="text-center mb-4">
            <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-person-plus-fill fs-2"></i>
            </div>
            <h2 class="fw-bold text-dark">Crear Cuenta</h2>
            <p class="text-muted small">Únete a nuestra comunidad de autores</p>
        </div>

        <form method="post" action="index.php?controller=auth&action=register" novalidate>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
          
          <div class="mb-3">
            <label for="nombre" class="form-label small text-muted fw-bold">Nombre Completo</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                <input type="text" class="form-control bg-light border-start-0 ps-0" id="nombre" name="nombre" placeholder="Tu nombre" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label small text-muted fw-bold">Correo Electrónico</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" class="form-control bg-light border-start-0 ps-0" id="email" name="email" placeholder="nombre@ejemplo.com" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label small text-muted fw-bold">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                <input type="password" class="form-control bg-light border-start-0 ps-0" id="password" name="password" placeholder="Crea una contraseña segura" required>
            </div>
          </div>

          <div class="mb-4">
            <label for="password2" class="form-label small text-muted fw-bold">Confirmar Contraseña</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock-fill text-muted"></i></span>
                <input type="password" class="form-control bg-light border-start-0 ps-0" id="password2" name="password2" placeholder="Repite la contraseña" required>
            </div>
          </div>

          <div class="d-grid mb-3">
             <button class="btn btn-success btn-lg rounded-3 shadow-sm">Registrarse</button>
          </div>
        </form>

        <div class="text-center mt-4">
            <p class="small text-muted mb-0">¿Ya tienes una cuenta?</p>
            <a href="index.php?controller=auth&action=login" class="text-decoration-none fw-bold text-dark">
                Inicia sesión aquí <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>

      </div>
    </div>
  </div>
</div><br>

<?php require __DIR__ . '/../layouts/footer.php'; ?>