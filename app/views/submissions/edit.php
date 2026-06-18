<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=submissions" class="text-decoration-none text-muted small fw-bold"><i class="fa-solid fa-arrow-left"></i> QUAY LẠI</a>
        <h2 class="fw-bold mt-2 text-primary">Cập Nhật Bài Nộp</h2>
        <p class="text-muted">Tải lên file báo cáo mới thay thế cho bản hiện tại.</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" style="max-width: 600px; margin-bottom: 20px;">
            <i class="fa-solid fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 600px;">
        <form action="index.php?page=submission-update&id=<?= $submission['id'] ?>" method="POST" enctype="multipart/form-data"> <?= csrfField() ?>
            <div class="mb-3">
                <label class="fw-bold small text-muted">CỘT MỐC</label>
                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($submission['milestone_title']) ?>" disabled>
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-muted">ĐỀ TÀI</label>
                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($submission['topic_title']) ?>" disabled>
            </div>

            <div class="mb-4">
                <label class="fw-bold small text-muted">FILE ?� N?P</label>
                <div class="form-control bg-light">
                    <?php if (!empty($submission['file_path'])): ?>
                        <a href="assets/uploads/submissions/<?= htmlspecialchars($submission['file_path']) ?>" target="_blank" class="text-decoration-none">
                            <i class="fa-solid fa-file-arrow-down me-1"></i> <?= htmlspecialchars(basename($submission['file_path'])) ?>
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Chưa có file</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-4">
                <label class="fw-bold small text-muted mb-2">CHỌN FILE MỚI ĐỂ THAY THẾ</label>
                <input type="file" name="report_file" class="form-control form-control-lg" required>
                <small class="text-muted mt-2 d-block">Lưu ý: Chỉ chấp nhận các định dạng pdf, doc, docx, zip, rar, png, jpg, jpeg (Tối đa 10MB).</small>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold text-white shadow-sm">Cập nhật file</button>
            <a href="index.php?page=submissions" class="btn btn-light rounded-pill px-4 ms-2">Hủy</a>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
