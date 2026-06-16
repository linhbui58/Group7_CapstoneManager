<?php
$pageTitle = '403 Forbidden';
require_once '../app/views/layouts/header.php';
?>
<div class="container text-center mt-5">
    <h1 class="display-1 text-danger">403</h1>
    <h2 class="mb-4">Access Denied</h2>
    <p class="lead">You do not have permission to access this page.</p>
    <?php if(isset($errorMessage)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>?page=dashboard" class="btn btn-primary mt-3">Return to Dashboard</a>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
