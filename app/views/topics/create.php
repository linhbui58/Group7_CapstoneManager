<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<?php $role = $_SESSION['user']['role']; ?>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=topics" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Trở về danh sách
        </a>
        <h2 class="fw-bold mt-2">
            <?= $role === 'admin' ? 'Thêm Đề Tài Mới' : 'Tạo & Đăng Ký Đề Tài' ?>
        </h2>
        <?php if ($role === 'student'): ?>
            <p class="text-muted small">Mô tả đề tài bạn muốn thực hiện và chọn giảng viên hướng dẫn mong muốn.</p>
        <?php endif; ?>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 800px;">
        <form action="index.php?page=topic-store" method="POST">

            <!-- Tiêu đề -->
            <div class="mb-3">
                <label class="fw-bold small text-muted">TIÊU ĐỀ ĐỀ TÀI <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control rounded-pill px-3"
                       placeholder="Nhập tên đề tài..." required>
            </div>

            <div class="row mb-3">
                <!-- Học kỳ -->
                <div class="col-md-<?= $role === 'admin' ? '6' : '12' ?>">
                    <label class="fw-bold small text-muted">HỌC KỲ <span class="text-danger">*</span></label>
                    <select name="semester_id" class="form-select rounded-pill px-3" required>
                        <option value="">-- Chọn học kỳ --</option>
                        <?php foreach ($semesters as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if ($role === 'admin'): ?>
                <!-- Trạng thái (admin only) -->
                <div class="col-md-6">
                    <label class="fw-bold small text-muted">TRẠNG THÁI</label>
                    <select name="status" class="form-select rounded-pill px-3">
                        <option value="pending">Chờ duyệt</option>
                        <option value="approved">Đã duyệt</option>
                    </select>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($role === 'student'): ?>
            <!-- Giảng viên mong muốn (student only) -->
            <div class="mb-3">
                <label class="fw-bold small text-muted">GIẢNG VIÊN HƯỚNG DẪN MONG MUỐN</label>
                <select name="desired_lecturer_id" class="form-select rounded-pill px-3">
                    <option value="">-- Không chỉ định --</option>
                    <?php foreach ($lecturers as $l): ?>
                        <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <!-- Keywords -->
            <div class="mb-3">
                <label class="fw-bold small text-muted">TỪ KHÓA</label>
                <input type="text" name="keywords" class="form-control rounded-pill px-3"
                       placeholder="AI, Machine Learning, Web...">
            </div>

            <!-- Mô tả -->
            <div class="mb-4">
                <label class="fw-bold small text-muted">MÔ TẢ</label>
                <textarea name="description" class="form-control" rows="4"
                          style="border-radius: 15px;"
                          placeholder="Mô tả chi tiết về đề tài..."></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                    <?= $role === 'admin' ? 'Lưu Đề Tài' : 'Gửi Đăng Ký' ?>
                </button>
                <a href="index.php?page=topics" class="btn btn-light rounded-pill px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
