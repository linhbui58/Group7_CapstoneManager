<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=supervisions" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Trở về danh sách
        </a>
        <h2 class="fw-bold mt-2">Phân Công Giảng Viên Hướng Dẫn</h2>
        <p class="text-muted small">Phân công giảng viên cho sinh viên. Sinh viên và giảng viên phải cùng khoa.</p>
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
                <form action="index.php?page=supervision-store" method="POST">
                    <?= csrfField() ?>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">HỌC KỲ <span class="text-danger">*</span></label>
                        <select name="semester_id" class="form-select rounded-pill" required>
                            <option value="">-- Chọn học kỳ --</option>
                            <?php foreach ($semesters as $sem): ?>
                                <option value="<?= $sem['id'] ?>"><?= htmlspecialchars($sem['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">SINH VIÊN <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-select rounded-pill" required>
                            <option value="">-- Chọn sinh viên --</option>
                            <?php foreach ($students as $stu): ?>
                                <option value="<?= $stu['id'] ?>" data-faculty="<?= htmlspecialchars($stu['faculty'] ?? '') ?>">
                                    <?= htmlspecialchars($stu['full_name']) ?>
                                    <?php if (!empty($stu['faculty'])): ?>
                                        — <?= htmlspecialchars($stu['faculty']) ?>
                                    <?php else: ?>
                                        — <em>Chưa có khoa</em>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">GIẢNG VIÊN <span class="text-danger">*</span></label>
                        <select name="lecturer_id" id="lecturer_id" class="form-select rounded-pill" required>
                            <option value="">-- Chọn giảng viên --</option>
                            <?php foreach ($lecturers as $lec): ?>
                                <option value="<?= $lec['id'] ?>" data-faculty="<?= htmlspecialchars($lec['faculty'] ?? '') ?>">
                                    <?= htmlspecialchars($lec['full_name']) ?>
                                    <?php if (!empty($lec['faculty'])): ?>
                                        — <?= htmlspecialchars($lec['faculty']) ?>
                                    <?php else: ?>
                                        — <em>Chưa có khoa</em>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted small mt-1">
                            <i class="fa fa-info-circle me-1"></i>Danh sách giảng viên sẽ tự động lọc theo khoa của sinh viên. Nếu không có kết quả, sinh viên hoặc giảng viên chưa cập nhật khoa.
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fa fa-save me-1"></i> Lưu Phân Công
                        </button>
                        <a href="index.php?page=supervisions" class="btn btn-light rounded-pill px-4">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const lecturerSelect = document.getElementById('lecturer_id');
    const lecturerOptions = Array.from(lecturerSelect.options); // Lưu lại bản gốc

    studentSelect.addEventListener('change', function() {
        const selectedStudent = this.options[this.selectedIndex];
        const studentFaculty = selectedStudent.getAttribute('data-faculty');

        // Giữ lại option mặc định
        const defaultOption = lecturerOptions[0];
        
        lecturerSelect.innerHTML = '';
        lecturerSelect.appendChild(defaultOption);

        if (studentFaculty) {
            lecturerOptions.forEach(opt => {
                if (opt.value && opt.getAttribute('data-faculty') === studentFaculty) {
                    lecturerSelect.appendChild(opt.cloneNode(true));
                }
            });
        } else {
            // Nếu chưa chọn sinh viên, có thể hiển thị tất cả hoặc không hiển thị ai (ở đây hiện tất cả)
            lecturerOptions.forEach(opt => {
                if(opt.value) lecturerSelect.appendChild(opt.cloneNode(true));
            });
        }
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
