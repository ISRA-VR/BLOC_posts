<?php
?>
</div> 

<style>
    .footer-custom {
        background: rgba(211, 234, 255, 1);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 -4px 20px rgba(0,0,0,0.02);
        place-items: center;
    }
</style>

<footer class="py-4 mt-auto footer-custom">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
        
        <div class="col-md-4 d-flex align-items-center">
            <!-- Texto adaptado al tema claro (ya no es text-white) -->
            <span class="text-muted fw-medium">
                © <?php echo date('Y'); ?> <strong style="color: var(--secondary-color);">Mi Blog</strong> Personal - Derechos reservados
            </span>
        </div>
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
        confirmButtonColor: '#764ba2', /* Coincide con var(--secondary-color) */
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar',
        // Estilos para que el modal coincida con la tipografía Outfit
        customClass: {
            popup: 'rounded-4 shadow-lg',
            confirmButton: 'rounded-pill px-4',
            cancelButton: 'rounded-pill px-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    })
}
</script>

</body>
</html>