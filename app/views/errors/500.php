<?php
$pageTitle = '500 Internal Server Error';
require_once '../app/views/layouts/header.php';
?>
<div class="container text-center mt-5">
    <h1 class="display-1 text-danger">500</h1>
    <h2 class="mb-4">Lỗi máy chủ nội bộ</h2>
    <p class="lead">Đã có sự cố xảy ra. Vui lòng thử lại sau.</p>
    <?php if(isset($errorMessage)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>?page=dashboard" class="btn btn-primary mt-3">Về trang chủ</a>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
