<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 30px; background-color: #f8fafc; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=lecturers" class="text-decoration-none text-muted small"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
        <h2 class="fw-bold mt-2">Hồ sơ giảng viên</h2>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 24px; border: 1px solid #f1f5f9; overflow: hidden;">
        <div class="card-body p-0">
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <td style="width: 30%; padding: 25px 30px;" class="fw-bold text-muted bg-light small">MÃ GIẢNG VIÊN</td>
                        <td style="padding: 25px 30px;" class="fw-bold text-primary">#<?= $lecturer['id'] ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 25px 30px;" class="fw-bold text-muted bg-light small">HỌ VÀ TÊN</td>
                        <td style="padding: 25px 30px;" class="fw-semibold"><?= htmlspecialchars($lecturer['full_name']) ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 25px 30px;" class="fw-bold text-muted bg-light small">ĐỊA CHỈ EMAIL</td>
                        <td style="padding: 25px 30px;"><?= htmlspecialchars($lecturer['email']) ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 25px 30px;" class="fw-bold text-muted bg-light small">CHUYÊN MÔN</td>
                        <td style="padding: 25px 30px;">
                            <span class="badge rounded-pill px-3 py-2" style="background: #f0fdf4; color: #16a34a; font-weight: 700;">
                                <?= htmlspecialchars($lecturer['expertise']) ?>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white p-4 border-0 d-flex gap-2">
            <a href="index.php?page=lecturer-edit&id=<?= $lecturer['id'] ?>" class="btn btn-warning rounded-pill px-4 fw-bold text-white shadow-sm">
                <i class="fa-solid fa-pen-to-square me-1"></i> Chỉnh sửa hồ sơ
            </a>
        </div>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>