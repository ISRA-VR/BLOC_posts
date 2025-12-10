<?php 
require __DIR__ . '/../layouts/header.php'; 
?>

<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
  <div class="col-md-5 col-lg-4">
    
    <div class="card card-custom border-0 shadow-lg rounded-4">
      <div class="card-body p-5">
        
        <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-person-fill fs-2"></i>
            </div>
            <h2 class="fw-bold text-dark">Iniciar sesión</h2>
            <p class="text-muted small">Bienvenido usuario</p>
        </div>

        <form method="post" action="index.php?controller=auth&action=login" novalidate>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
          
          <div class="mb-4">
            <label for="email" class="form-label small text-muted fw-bold">Correo Electrónico</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" class="form-control bg-light border-start-0 ps-0" id="email" name="email" placeholder="nombre@ejemplo.com" required>
            </div>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label small text-muted fw-bold">Contraseña</label>
            <div class="input-group">
                <!-- Icono candado izquierda -->
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                
                <!-- Input contraseña (agregué border-end-0) -->
                <input type="password" class="form-control bg-light border-start-0 border-end-0 ps-0" id="password" name="password" placeholder="••••••••" required>
                
                <!-- BOTÓN DEL OJO (NUEVO) -->
                <button class="btn bg-light border-start-0" type="button" id="togglePassword" style="border-color: #dee2e6;">
                    <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                </button>
            </div>
          </div>

          <div class="d-grid mb-3">
             <!-- Asegurate de tener la clase .btn-brand en tu CSS, si no usa btn-dark -->
             <button class="btn btn-brand btn-lg rounded-3">Entrar</button>
          </div>
        </form>

        <div class="text-center mt-4">
            <p class="small text-muted mb-0">¿No tienes cuenta?</p>
            <a href="index.php?controller=auth&action=register" class="text-decoration-none fw-bold text-primary">
                Regístrate aquí <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>

      </div>
    </div>

  </div>
</div><br>

<!-- Script para intercalar la contraseña -->
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function (e) {
        // Alternar el atributo type
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Alternar el icono del ojo (ojo abierto / ojo tachado)
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>