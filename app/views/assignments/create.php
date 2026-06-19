<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc; min-height: 100vh;">
    <div class="mx-auto" style="max-width: 800px;">
        <div class="mb-4">
            <a href="index.php?page=assignments" class="text-decoration-none text-muted small">
                <i class="fa-solid fa-arrow-left me-1"></i> Trở về danh sách
            </a>
            <h2 class="fw-bold mt-2">Tạo Phân Công Mới</h2>
        </div>

        <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; width: 100%;">
        <form action="index.php?page=assignment-store" method="POST"> <?= csrfField() ?>
            <div class="mb-4">
                <label class="fw-bold small text-muted mb-2">CHỌN ĐỀ TÀI</label>
                <select name="topic_id" class="form-select rounded-pill px-3" required style="height: 50px;">
                    <option value="">-- Chọn đề tài --</option>
                    <?php foreach($topics as $topic): ?>
                        <option value="<?= $topic['id'] ?>"><?= htmlspecialchars($topic['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="fw-bold small text-muted mb-2">GIẢNG VIÊN PHỤ TRÁCH</label>
                <select name="lecturer_id" class="form-select rounded-pill px-3" required style="height: 50px;">
                    <option value="">-- Chọn giảng viên --</option>
                    <!-- Sẽ được populate bằng JavaScript khi chọn đề tài -->
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow">Lưu Phân Công</button>
                <a href="index.php?page=assignments" class="btn btn-light rounded-pill px-4 ms-2">Hủy</a>
            </div>
        </form>
        </div>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>

<script>
const topicFaculties = {
    <?php foreach($topics as $t): ?>
    "<?= $t['id'] ?>": "<?= htmlspecialchars($t['student_faculty'] ?? '') ?>",
    <?php endforeach; ?>
};

const lecturers = [
    <?php foreach($lecturers as $l): ?>
    { id: "<?= $l['id'] ?>", name: "<?= htmlspecialchars($l['full_name']) ?>", faculty: "<?= htmlspecialchars($l['faculty'] ?? '') ?>" },
    <?php endforeach; ?>
];

document.querySelector('select[name="topic_id"]').addEventListener('change', function() {
    const topicId = this.value;
    const lecturerSelect = document.querySelector('select[name="lecturer_id"]');
    lecturerSelect.innerHTML = '<option value="">-- Chọn giảng viên --</option>';
    
    if (!topicId) {
        // Không hiển thị giảng viên nào nếu chưa chọn đề tài
        return;
    }
    
    const requiredFaculty = topicFaculties[topicId];
    
    lecturers.forEach(l => {
        if (!requiredFaculty || l.faculty === requiredFaculty) {
            lecturerSelect.innerHTML += `<option value="${l.id}">${l.name}</option>`;
        }
    });
});
</script>

