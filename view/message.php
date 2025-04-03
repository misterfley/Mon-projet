<?php if (isset($_GET['message']) && isset($_GET['status'])): ?>
    <div class="alert alert-<?php echo htmlspecialchars($_GET['status']); ?> text-center m-3" role="alert" id="flash-message">
        <?php echo htmlspecialchars($_GET['message']); ?>
    </div>


<?php endif; ?> <script>
    const flash = document.getElementById('flash-message');
    if (flash) {
        setTimeout(() => {
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }, 4000); // disparaît après 4s
    }
</script>