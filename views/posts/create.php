<?php 
// ==========================================
// VISTA: CREAR POST
// ==========================================
// Muestra un formulario para enviar datos al método create() del controlador.

require __DIR__ . '/../layouts/header.php'; 
?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card card-custom p-4">
        <div class="card-body">
            <h2 class="fw-bold mb-4 text-center text-primary">Crear Nuevo Post</h2>
            
            <form method="post" action="index.php?controller=post&action=create" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'] ?? '')?>">
              
              <div class="mb-4">
                <label for="titulo" class="form-label fw-bold text-secondary">Título del Post</label>
                <input type="text" class="form-control form-control-lg bg-light border-0" id="titulo" name="titulo" placeholder="Escribe un título llamativo..." required>
              </div>
              
              <div class="mb-4">
                <label class="form-label fw-bold text-secondary">Imagen Destacada</label>
                <div id="drop-zone" class="border rounded-3 p-5 text-center bg-light position-relative" style="border: 2px dashed #cbd5e0 !important; cursor: pointer; transition: all 0.3s;">
                    <div id="upload-prompt">
                        <i class="bi bi-cloud-arrow-up text-primary mb-3" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold text-dark">Arrastra tu imagen aquí</h5>
                        <p class="text-muted small">o haz clic para explorar</p>
                    </div>
                    <input type="file" name="imagen" id="imagen" class="d-none" accept="image/*">
                    
                    <div id="preview-container" class="mt-3 d-none">
                        <img id="preview-image" src="" alt="Vista previa" class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                        <div class="mt-2">
                            <span id="file-name" class="badge bg-secondary"></span>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearImage(event)">Quitar</button>
                        </div>
                    </div>
                </div>
              </div>

              <div class="mb-4">
                <label for="contenido" class="form-label fw-bold text-secondary">Contenido</label>
                <textarea class="form-control bg-light border-0" id="contenido" name="contenido" rows="8" placeholder="¿Qué estás pensando hoy?" required></textarea>
              </div>
              
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                  <a class="btn btn-light text-secondary fw-bold px-4 rounded-pill" href="index.php?controller=posts&action=index">Cancelar</a>
                  <button class="btn btn-brand px-5 rounded-pill shadow-lg">Publicar Post</button>
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

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.remove('bg-light');
        dropZone.style.backgroundColor = '#ebf4ff';
        dropZone.style.borderColor = '#667eea';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.add('bg-light');
        dropZone.style.backgroundColor = '';
        dropZone.style.borderColor = '#cbd5e0';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.add('bg-light');
        dropZone.style.backgroundColor = '';
        dropZone.style.borderColor = '#cbd5e0';
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            showPreview(fileInput.files[0]);
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
    }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>