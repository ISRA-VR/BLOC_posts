
<?php
// footer.php
?>
</div> <!-- .container -->
<footer class="footer mt-5 py-3 bg-light">
  <div class="container text-center">
    <span class="text-muted">Blog MVC - Hecho con PHP, MySQL, HTML, CSS3 y Bootstrap opcional</span>
  </div>
</footer>

<!-- Bootstrap JS (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Script pequeño para confirmaciones -->
<script>
function confirmarEliminar(url) {
    if (confirm('¿Estás seguro que quieres eliminar este post?')) {
        window.location.href = url;
    }
}
</script>
</body>
</html>