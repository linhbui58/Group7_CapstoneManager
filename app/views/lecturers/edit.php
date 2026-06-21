<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 30px; background-color: #f8fafc;">
    <div class="mb-4">
        <a href="<?= $_SESSION['user']['role']==='lecturer' ? 'index.php?page=dashboard' : 'index.php?page=lecturers' ?>" class="text-decoration-none text-muted small"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
        <h2 class="fw-bold mt-2">Chỉnh sửa giảng viên</h2>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 600px; border: 1px solid #f1f5f9;">
        <form action="index.php?page=lecturer-update&id=<?= $lecturer['id'] ?>" method="POST"> <?= csrfField() ?>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">HỌ VÀ TÊN</label>
                <input type="text" name="full_name" class="form-control rounded-pill px-3" value="<?= htmlspecialchars($lecturer['full_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">CHUYÊN MÔN</label>
                <input type="text" name="expertise" class="form-control rounded-pill px-3" value="<?= htmlspecialchars($lecturer['expertise'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">KHOA/VIỆN</label>
                <select name="faculty" class="form-select rounded-pill px-3">
                    <option value="">-- Chọn Khoa --</option>
                    <?php foreach (['Khoa CNTT', 'Khoa Kinh tế', 'Khoa Ngôn ngữ'] as $f): ?>
                        <option value="<?= $f ?>" <?= ($lecturer['faculty'] ?? '') === $f ? 'selected' : '' ?>><?= $f ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">EMAIL (Chỉ xem)</label>
                <input type="text" class="form-control rounded-pill px-3 bg-light" value="<?= htmlspecialchars($lecturer['email']) ?>" readonly>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold shadow-sm text-white">Lưu thay đổi</button>
                <a href="<?= $_SESSION['user']['role']==='lecturer' ? 'index.php?page=dashboard' : 'index.php?page=lecturers' ?>" class="btn btn-light rounded-pill px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>