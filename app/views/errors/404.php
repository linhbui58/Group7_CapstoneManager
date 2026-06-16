<?php
$pageTitle = '404 Not Found';
require_once '../app/views/layouts/header.php';
?>
<div class="container text-center mt-5">
    <h1 class="display-1 text-warning">404</h1>
    <h2 class="mb-4">Page Not Found</h2>
    <p class="lead">The page you are looking for does not exist or has been moved.</p>
    <a href="<?= BASE_URL ?>?page=dashboard" class="btn btn-primary mt-3">Return to Dashboard</a>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
