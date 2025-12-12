<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card card-custom p-4">
        <div class="card-body">
            <h2 class="fw-bold mb-4 text-center text-primary">Editar Post</h2>
            
            <!-- Formulario de edición del post -->
            <form method="post" action="index.php?controller=post&action=edit&id=<?= $post['id'] ?>" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'] ?? '')?>">
              
              <div class="mb-4">
                <label for="titulo" class="form-label fw-bold text-secondary">Título del Post</label>
                <!-- Valor actual del título -->
                <input type="text" class="form-control form-control-lg bg-light border-0" id="titulo" name="titulo" placeholder="Escribe un título llamativo..." required value="<?= htmlspecialchars($post['titulo']) ?>">
              </div>
              
              <div class="mb-4">
                <label class="form-label fw-bold text-secondary">Imagen Destacada</label>
                <div id="drop-zone" class="dropzone border rounded-3 p-5 text-center bg-light position-relative">
                    <div id="upload-prompt">
                        <i class="bi bi-cloud-arrow-up text-primary mb-3 fs-1"></i>
                        <h5 class="fw-bold text-dark">Arrastra tu imagen aquí</h5>
                        <p class="text-muted small">o haz clic para explorar</p>
                    </div>
                    <input type="file" name="imagen" id="imagen" class="d-none" accept="image/*">
                    
                    <div id="preview-container" class="mt-3 <?= empty($post['imagen']) ? 'd-none' : '' ?>">
                        <div class="overflow-hidden" style="height:300px;">
                            <img id="preview-image" src="<?= !empty($post['imagen']) ? htmlspecialchars($post['imagen']) : '' ?>" alt="Vista previa" class="w-100 h-100 rounded shadow-sm img-cover">
                        </div>
                        <div class="mt-2">
                            <span id="file-name" class="badge bg-secondary">Imagen actual</span>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearImage(event)">Quitar</button>
                        </div>
                    </div>
                </div>
                <!-- Advertencia cuando no hay imagen seleccionada -->
                <div id="image-warning" class="alert alert-warning mt-3 d-none" role="alert">
                    La imagen es obligatoria. Si la quitas, selecciona otra antes de actualizar.
                </div>
              </div>

              <div class="mb-4">
                <label for="contenido" class="form-label fw-bold text-secondary">Contenido</label>
                <!-- En textarea el valor va entre las etiquetas -->
                <textarea class="form-control bg-light border-0" id="contenido" name="contenido" rows="8" placeholder="¿Qué estás pensando hoy?" required><?= htmlspecialchars($post['contenido']) ?></textarea>
              </div>
              
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                  <a class="btn btn-light text-secondary fw-bold px-4 rounded-pill" href="index.php?controller=posts&action=index">Cancelar</a>
                  <button class="btn btn-brand px-5 rounded-pill shadow-lg">Actualizar Post</button>
              </div>
            </form>
        </div>
    </div>
  </div>
</div>

<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('imagen');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const fileName = document.getElementById('file-name');
    const uploadPrompt = document.getElementById('upload-prompt');
    const imageWarning = document.getElementById('image-warning');
    const hadInitialImage = <?= empty($post['imagen']) ? 'false' : 'true' ?>;

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.remove('bg-light');
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.add('bg-light');
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.add('bg-light');
        dropZone.classList.remove('dragover');
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            showPreview(fileInput.files[0]);
            imageWarning.classList.add('d-none');
        }
    });

    function showPreview(file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('d-none');
                uploadPrompt.classList.add('d-none');
                fileName.textContent = file.name;
                imageWarning.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        }
    }

    function clearImage(e) {
        e.stopPropagation(); // Evitar abrir el selector de archivos
        fileInput.value = '';
        previewContainer.classList.add('d-none');
        uploadPrompt.classList.remove('d-none');
        previewImage.src = '';
        // Mostrar advertencia si no hay imagen seleccionada
        imageWarning.classList.remove('d-none');
    }

    // Advertir al enviar si no hay imagen (ni previa ni nueva)
    const form = document.querySelector('form[action^="index.php?controller=post&action=edit"]');
    if (form) {
        form.addEventListener('submit', (ev) => {
            const noPreview = previewContainer.classList.contains('d-none');
            const noNewFile = !fileInput.files || fileInput.files.length === 0;
            if (noPreview && noNewFile) {
                imageWarning.classList.remove('d-none');
                imageWarning.scrollIntoView({ behavior: 'smooth', block: 'center' });
                ev.preventDefault();
            }
        });
    }
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>