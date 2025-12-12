<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold text-dark"><i class="bi bi-grid-fill me-2 text-primary"></i>Explorar Posts</h2>
  <div>
    <a class="btn btn-brand" href="index.php?controller=post&action=create">
        <i class="bi bi-plus-lg me-1"></i> Crear Post
    </a>
  </div>
</div>

<?php if (empty($posts)): ?>
  <div class="text-center py-5">
      <div class="mb-3 text-muted" style="font-size: 3rem;"><i class="bi bi-inbox"></i></div>
      <h4 class="text-muted">No hay publicaciones aún</h4>
      <p class="text-muted">Sé el primero en compartir algo interesante.</p>
  </div>
<?php else: ?>
  <div class="row g-4">
    <?php foreach ($posts as $p): ?>
      <div class="col-md-6 col-lg-4">
          <div class="card card-custom h-100" 
               style="cursor: pointer;"
               data-title="<?=htmlspecialchars($p['titulo'])?>" 
               data-content="<?=htmlspecialchars($p['contenido'])?>"
               data-image="<?=!empty($p['imagen']) ? htmlspecialchars($p['imagen']) : ''?>"
               onclick="openModal(this)">
            
            <?php if(!empty($p['imagen'])): ?>
                <div class="overflow-hidden" style="height:200px;">
                    <img src="<?=htmlspecialchars($p['imagen'])?>" class="card-img-top w-100 h-100 img-cover" alt="Imagen del post">
                </div>
            <?php else: ?>
                <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
                    <i class="bi bi-image" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            <?php endif; ?>

            <div class="card-body d-flex flex-column">
              <div class="mb-2">
                  <span class="badge bg-light text-primary border border-primary-subtle rounded-pill">
                      <i class="bi bi-person-circle me-1"></i><?=htmlspecialchars($p['autor_nombre'])?>
                  </span>
                  <small class="text-muted ms-2"><i class="bi bi-clock me-1"></i><?=date('d M', strtotime($p['fecha_creacion']))?></small>
              </div>
              
              <h5 class="card-title fw-bold mb-3 text-dark"><?=htmlspecialchars($p['titulo'])?></h5>
              
              <p class="card-text text-secondary flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                  <?=nl2br(htmlspecialchars($p['contenido']))?>
              </p>
              
              <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center" onclick="event.stopPropagation()">
                  <div class="btn-group">
                      <a class="btn btn-sm btn-outline-secondary rounded-pill me-1" href="index.php?controller=post&action=edit&id=<?=$p['id']?>">
                          <i class="bi bi-pencil"></i>
                      </a>
                      <form method="post" action="index.php?controller=post&action=delete" onsubmit="return confirm('¿Eliminar este post?');" style="display:inline;">
                        <input type="hidden" name="id" value="<?=$p['id']?>">
                        <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'] ?? '')?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                            <i class="bi bi-trash"></i>
                        </button>
                      </form>
                  </div>
                  <small class="text-primary fw-bold"></small>
              </div>
            </div>
          </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<!-- Modal -->
<div id="postModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content bg-white p-4 rounded shadow-lg" style="width: 90%; max-width: 600px; position: relative; max-height: 90vh; overflow-y: auto;">
        <span class="close-btn" onclick="closeModal()" style="position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer; font-weight: bold;">&times;</span>
        <h3 id="modalTitle" class="mb-3 fw-bold text-primary"></h3>
        <img id="modalImage" src="" alt="Imagen del post" class="img-fluid mb-3 d-none rounded shadow-sm" style="max-height: 400px; width: 100%; object-fit: contain;">
        <div id="modalContent" class="text-secondary" style="white-space: pre-wrap; line-height: 1.6;"></div>
    </div>
</div>

<script>
    function openModal(element) {
        const title = element.getAttribute('data-title');
        const content = element.getAttribute('data-content');
        const image = element.getAttribute('data-image');

        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalContent').textContent = content;
        
        const imgElement = document.getElementById('modalImage');
        if (image) {
            imgElement.src = image;
            imgElement.classList.remove('d-none');
        } else {
            imgElement.classList.add('d-none');
        }

        document.getElementById('postModal').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    function closeModal() {
        document.getElementById('postModal').style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }

    // Cerrar al hacer click fuera del modal
    document.getElementById('postModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Cerrar con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>