<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=milestones" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> TRỞ VỀ DANH SÁCH
        </a>
        <h2 class="fw-bold mt-2" style="color: #0f172a;">Thiết Lập Cột Mốc Mới</h2>
    </div>

    <div class="card border-0 shadow-sm p-5 mx-auto" style="border-radius: 25px; max-width: 600px;">
        <form action="index.php?page=milestone-store" method="POST">
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">GIAI ĐOẠN ĐÁNH GIÁ</label>
                <select name="title" class="form-select rounded-pill px-3 shadow-none" style="height: 50px;" required>
                    <option value="proposal">Đề cương nghiên cứu (Proposal)</option>
                    <option value="midterm">Báo cáo tiến độ (Midterm)</option>
                    <option value="final">Báo cáo nghiệm thu (Final)</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">ÁP DỤNG CHO HỌC KỲ</label>
                <select name="semester_id" class="form-select rounded-pill px-3 shadow-none" style="height: 50px;" required>
                    <option value="">-- Chọn học kỳ --</option>
                    <?php foreach($semesters as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold small text-muted">THỜI HẠN KHÓA CỔNG NỘP BÀI</label>
                <input type="datetime-local" name="deadline" class="form-control rounded-pill px-3 shadow-none" style="height: 50px;" required>
                <p class="form-text small text-danger mt-2 ms-2"><i class="fa-solid fa-circle-info"></i> Sau thời gian này, sinh viên sẽ không thể nộp bài vào hệ thống.</p>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow">
                    KÍCH HOẠT CỘT MỐC
                </button>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>