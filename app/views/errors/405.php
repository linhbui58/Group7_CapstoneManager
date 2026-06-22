<?php
$pageTitle = '405 Method Not Allowed';
require_once '../app/views/layouts/header.php';
?>
<div class="container text-center mt-5">
    <h1 class="display-1 text-danger">405</h1>
    <h2 class="mb-4">Phương thức không được phép</h2>
    <p class="lead">Yêu cầu này không được phép thực hiện theo phương thức hiện tại.</p>
    <?php if(isset($errorMessage)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>?page=dashboard" class="btn btn-primary mt-3">Về trang chủ</a>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
