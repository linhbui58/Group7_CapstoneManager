<?php
$pageTitle = '404 Not Found';
require_once '../app/views/layouts/header.php';
?>
<div class="container text-center mt-5">
    <h1 class="display-1 text-warning">404</h1>
    <h2 class="mb-4">Không tìm thấy trang</h2>
    <p class="lead">Trang bạn tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
    <a href="<?= BASE_URL ?>?page=dashboard" class="btn btn-primary mt-3">Về trang chủ</a>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
