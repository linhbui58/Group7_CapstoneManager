<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding:32px; background:#f4f7fe; min-height:100vh;">
    <div class="mb-4">
        <a href="index.php?page=students" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> QUAY LẠI
        </a>
        <h2 class="fw-bold mt-2" style="color:#0f172a">Thêm sinh viên mới</h2>
        <p class="text-muted small mt-1">Tạo tài khoản và hồ sơ sinh viên cùng lúc</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
            <i class="fa-solid fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-4" style="border-radius:20px; max-width:600px">
        <form method="POST" action="index.php?page=student-store">

            <div class="mb-3">
                <label class="fw-bold small text-muted">HỌ VÀ TÊN <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control rounded-pill px-3"
                       placeholder="Nguyễn Văn A" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-muted">MÃ SINH VIÊN <span class="text-danger">*</span></label>
                <input type="text" name="student_code" class="form-control rounded-pill px-3"
                       placeholder="SV001" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-muted">EMAIL <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control rounded-pill px-3"
                       placeholder="student@example.com" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-muted">MẬT KHẨU <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control rounded-pill px-3"
                       placeholder="Tối thiểu 6 ký tự" required>
            </div>

            <div class="mb-4">
                <label class="fw-bold small text-muted">SỐ ĐIỆN THOẠI</label>
                <input type="text" name="phone" class="form-control rounded-pill px-3"
                       placeholder="0901234567">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm"
                        style="background:linear-gradient(135deg,#6366f1,#4f46e5);border:none">
                    <i class="fa-solid fa-user-plus me-2"></i>Tạo sinh viên
                </button>
                <a href="index.php?page=students" class="btn btn-light rounded-pill px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
