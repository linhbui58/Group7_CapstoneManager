<?php
$pageTitle = '500 Internal Server Error';
require_once '../app/views/layouts/header.php';
?>
<div class="container text-center mt-5">
    <h1 class="display-1 text-danger">500</h1>
    <h2 class="mb-4">Internal Server Error</h2>
    <p class="lead">Something went wrong on our end. Please try again later.</p>
    <?php if(isset($errorMessage)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>?page=dashboard" class="btn btn-primary mt-3">Return to Dashboard</a>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
