<?php
// views/layouts/footer.php
?>
</div> 

<footer class="py-4 mt-auto shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
        
        <div class="col-md-4 d-flex align-items-center">
            <!-- Texto en blanco -->
            <span class="text-white">© <?php echo date('Y'); ?> <strong>Mi Blog</strong> Personal - Derechos reservados</span>
        </div>

        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
            <li class="ms-3">
                <!-- Icono Github Blanco -->
                <a class="text-white" href="https://github.com/ISRA-VR" target="_blank" style="font-size: 1.5rem; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                    <i class="bi bi-github"></i>
                </a>
            </li>
            <li class="ms-3">
                <!-- Icono Facebook Blanco -->
                <a class="text-white" href="https://www.facebook.com/isra.IVR01/?locale=es_LA" target="_blank" style="font-size: 1.5rem; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                    <i class="bi bi-facebook"></i>
                </a>
            </li>
            <li class="ms-3">
                <!-- Icono Email Blanco -->
                <a class="text-white" href="mailto:israelvalerdi65@gmail.com" style="font-size: 1.5rem; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                    <i class="bi bi-envelope"></i>
                </a>
            </li>
        </ul>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmarEliminar(url) {
    Swal.fire({
        title: '¿Borrar post?',
        text: "No podrás revertir esto.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#764ba2',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    })
}
</script>

</body>
</html>