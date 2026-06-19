<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc; min-height: 100vh;">
    <div class="mx-auto" style="max-width: 800px;">
        <div class="mb-4">
            <a href="index.php?page=assignments" class="text-decoration-none text-muted small">
                <i class="fa-solid fa-arrow-left me-1"></i> Trở về danh sách
            </a>
            <h2 class="fw-bold mt-2">Cập Nhật Phân Công</h2>
        </div>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger mb-4"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; width: 100%;">
        <form action="index.php?page=assignment-update&id=<?= $assignment['id'] ?>" method="POST">
            <div class="mb-4">
                <label class="fw-bold small text-muted mb-2">CHỌN ĐỀ TÀI</label>
                <select name="topic_id" class="form-select rounded-pill px-3" required style="height: 50px;">
                    <option value="">-- Chọn đề tài --</option>
                    <?php foreach($topics as $topic): ?>
                        <option value="<?= $topic['id'] ?>" <?= $topic['id'] == $assignment['topic_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($topic['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="fw-bold small text-muted mb-2">GIẢNG VIÊN PHỤ TRÁCH</label>
                <select name="lecturer_id" class="form-select rounded-pill px-3" required style="height: 50px;">
                    <option value="">-- Chọn giảng viên --</option>
                    <!-- Sẽ được populate bằng JavaScript dựa trên đề tài đang chọn -->
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

const currentLecturerId = "<?= htmlspecialchars($assignment['lecturer_id'] ?? '') ?>";

function populateLecturers(topicId, selectedLecturerId = '') {
    const lecturerSelect = document.querySelector('select[name="lecturer_id"]');
    lecturerSelect.innerHTML = '<option value="">-- Chọn giảng viên --</option>';
    
    if (!topicId) {
        return; // Không hiện giảng viên nếu chưa chọn đề tài
    }
    
    const requiredFaculty = topicFaculties[topicId];
    
    lecturers.forEach(l => {
        if (!requiredFaculty || l.faculty === requiredFaculty) {
            let selectedAttr = (l.id === selectedLecturerId) ? 'selected' : '';
            lecturerSelect.innerHTML += `<option value="${l.id}" ${selectedAttr}>${l.name}</option>`;
        }
    });
}

document.querySelector('select[name="topic_id"]').addEventListener('change', function() {
    populateLecturers(this.value);
});

// Chạy ngay khi load trang để hiển thị giảng viên hiện tại
const initialTopicId = document.querySelector('select[name="topic_id"]').value;
populateLecturers(initialTopicId, currentLecturerId);
</script>
