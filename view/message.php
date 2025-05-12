<?php
if (isset($_GET['message']) && isset($_GET['status'])):
    $allowed_statuses = ['success', 'danger', 'warning', 'info', 'primary', 'secondary', 'dark', 'light'];
    $status = in_array($_GET['status'], $allowed_statuses) ? $_GET['status'] : 'info';
    $message = htmlspecialchars($_GET['message'], ENT_QUOTES);
?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
        <div id="flash-message" class="toast align-items-center text-bg-<?= $status ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?= $message ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            const toast = document.getElementById('flash-message');
            if (toast) {
                toast.classList.remove('show');
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 500);
            }
        }, 4000);
    </script>
<?php endif; ?>