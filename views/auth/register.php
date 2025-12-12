<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
  <div class="col-md-6 col-lg-5">

    <!-- Nota: Si quieres el borde de color arriba, agrega la clase 'card-custom' aquí -->
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

          <!-- CONTRASEÑA 1 -->
          <div class="mb-3">
            <label for="password" class="form-label small text-muted fw-bold">Contraseña</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
              <!-- Agregado border-end-0 -->
              <input type="password" class="form-control bg-light border-start-0 border-end-0 ps-0" id="password" name="password" placeholder="Crea una contraseña segura" required pattern="(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}" title="Contraseña mínima de 8 caracteres con mayúscula, número y símbolo." aria-describedby="passwordHelp passwordStrength">
              <!-- Botón Ojo 1 -->
              <button class="btn bg-light border-start-0" type="button" id="togglePass1" style="border-color: #dee2e6;">
                <i class="bi bi-eye text-muted" id="icon1"></i>
              </button>
            </div>
            <div class="form-text text-muted d-none" id="passwordHelp">Contraseña mínima de 8 caracteres con mayúscula, número y símbolo.</div>
            <div class="progress d-none" role="progressbar" aria-label="Fuerza de la contraseña" aria-valuemin="0" aria-valuemax="100" style="height: 8px;">
              <div class="progress-bar" id="passwordStrength" style="width: 0%;"></div>
            </div>
          </div>

          <!-- CONTRASEÑA 2 -->
          <div class="mb-4">
            <label for="password2" class="form-label small text-muted fw-bold">Confirmar Contraseña</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock-fill text-muted"></i></span>
              <!-- Agregado border-end-0 -->
              <input type="password" class="form-control bg-light border-start-0 border-end-0 ps-0" id="password2" name="password2" placeholder="Repite la contraseña" required pattern="(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}" title="Contraseña mínima de 8 caracteres con mayúscula, número y símbolo." aria-describedby="passwordMatchHelp">
              <!-- Botón Ojo 2 -->
              <button class="btn bg-light border-start-0" type="button" id="togglePass2" style="border-color: #dee2e6;">
                <i class="bi bi-eye text-muted" id="icon2"></i>
              </button>
            </div>
            <div class="form-text text-muted d-none" id="passwordMatchHelp">Debe coincidir y cumplir el requisito de seguridad.</div>
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

<!-- Script para manejar los dos ojitos -->
<script>
  function setupToggle(buttonId, inputId, iconId) {
    const button = document.getElementById(buttonId);
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    button.addEventListener('click', function() {
      // Cambiar tipo de input
      const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
      input.setAttribute('type', type);

      // Cambiar icono
      icon.classList.toggle('bi-eye');
      icon.classList.toggle('bi-eye-slash');
    });
  }

  // Inicializar para ambos campos
  setupToggle('togglePass1', 'password', 'icon1');
  setupToggle('togglePass2', 'password2', 'icon2');

  // Fuerza de contraseña y coincidencia en vivo
  const passInput = document.getElementById('password');
  const pass2Input = document.getElementById('password2');
  const strengthBar = document.getElementById('passwordStrength');
  const matchHelp = document.getElementById('passwordMatchHelp');
  const passwordHelp = document.getElementById('passwordHelp');
  const progressContainer = passwordHelp.nextElementSibling; // el div .progress

  function calcStrength(p) {
    let score = 0;
    if (p.length >= 8) score += 25;
    if (/[A-Z]/.test(p)) score += 25;
    if (/[0-9]/.test(p)) score += 25;
    if (/[^A-Za-z0-9]/.test(p)) score += 25;
    return score;
  }

  function updateStrength() {
    const val = passInput.value || '';
    const score = calcStrength(val);
    strengthBar.style.width = score + '%';
    strengthBar.classList.remove('bg-danger', 'bg-warning', 'bg-success');
    if (score < 50) {
      strengthBar.classList.add('bg-danger');
    } else if (score < 75) {
      strengthBar.classList.add('bg-warning');
    } else {
      strengthBar.classList.add('bg-success');
    }

    // Mostrar/ocultar ayuda y barra
    const show = val.length > 0 || document.activeElement === passInput;
    passwordHelp.classList.toggle('d-none', !show);
    progressContainer.classList.toggle('d-none', !show);
  }

  function updateMatch() {
    const match = passInput.value === pass2Input.value && pass2Input.value.length > 0;
    matchHelp.textContent = match ? 'Las contraseñas coinciden.' : 'Debe coincidir y cumplir el requisito de seguridad.';
    matchHelp.classList.toggle('text-success', match);
    matchHelp.classList.toggle('text-muted', !match);

    // Mostrar sólo si el usuario empezó a escribir en confirmación
    const show = pass2Input.value.length > 0 || document.activeElement === pass2Input;
    matchHelp.classList.toggle('d-none', !show);
  }

  passInput.addEventListener('input', () => {
    updateStrength();
    updateMatch();
  });
  pass2Input.addEventListener('input', updateMatch);
  passInput.addEventListener('focus', updateStrength);
  pass2Input.addEventListener('focus', updateMatch);
  // Inicial: oculto hasta que el usuario escriba o enfoque
  passwordHelp.classList.add('d-none');
  progressContainer.classList.add('d-none');
  matchHelp.classList.add('d-none');
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>