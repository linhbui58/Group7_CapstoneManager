<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 32px; background: #f4f7fe; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=students" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> QUAY LẠI
        </a>
        <h2 class="fw-bold mt-2" style="color:#0f172a">Chỉnh sửa sinh viên</h2>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger rounded-3"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-4" style="border-radius:20px; max-width:600px">
        <form method="POST" action="index.php?page=student-update&id=<?= $student['id'] ?>">

            <div class="mb-3">
                <label class="fw-bold small text-muted">MÃ SINH VIÊN</label>
                <input type="text" name="student_code" class="form-control rounded-pill px-3"
                       value="<?= htmlspecialchars($student['student_code'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-muted">HỌ VÀ TÊN</label>
                <input type="text" name="full_name" class="form-control rounded-pill px-3"
                       value="<?= htmlspecialchars($student['full_name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-muted">SỐ ĐIỆN THOẠI</label>
                <input type="text" name="phone" class="form-control rounded-pill px-3"
                       value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
            </div>

            <div class="mb-4">
                <label class="fw-bold small text-muted">EMAIL (chỉ xem)</label>
                <input type="text" class="form-control rounded-pill px-3 bg-light"
                       value="<?= htmlspecialchars($student['email'] ?? '') ?>" readonly>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold text-white shadow-sm">
                    Cập nhật
                </button>
                <a href="index.php?page=students" class="btn btn-light rounded-pill px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
