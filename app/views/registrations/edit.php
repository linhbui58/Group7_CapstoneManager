<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=topic-management&tab=registrations" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Trở về danh sách
        </a>
        <h2 class="fw-bold mt-2">Sửa Đề Xuất Đề Tài</h2>
        <p class="text-muted small">Cập nhật đề tài và ghi chú cho đề xuất đang chờ duyệt.</p>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                <form action="index.php?page=registration-update" method="POST"> <?= csrfField() ?>
                    <input type="hidden" name="id" value="<?= $reg['id'] ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">CHỌN ĐỀ TÀI <span class="text-danger">*</span></label>
                        <?php if (empty($topics)): ?>
                            <div class="alert alert-warning rounded-3 small">
                                Hiện chưa có đề tài nào được duyệt. Vui lòng quay lại sau.
                            </div>
                        <?php else: ?>
                            <select name="topic_id" class="form-select rounded-pill" required>
                                <option value="">-- Chọn đề tài --</option>
                                <?php foreach ($topics as $t): ?>
                                    <option value="<?= $t['id'] ?>" <?= $t['id'] == $reg['topic_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <!-- Ghi chú / keywords -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">GHI CHÚ / KEYWORDS</label>
                        <textarea name="keywords" class="form-control" style="border-radius: 15px;" rows="3"
                                  placeholder="Nhập các từ khóa hoặc ghi chú liên quan..."><?= htmlspecialchars($reg['keywords']) ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm"
                                <?= empty($topics) ? 'disabled' : '' ?>>
                            Cập Nhật Đề Xuất
                        </button>
                        <a href="index.php?page=topic-management&tab=registrations" class="btn btn-light rounded-pill px-4">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
