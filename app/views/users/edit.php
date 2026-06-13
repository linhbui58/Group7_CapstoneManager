<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding:32px; background:#f4f7fe; min-height:100vh;">
    <div class="mb-4">
        <a href="index.php?page=users" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> QUAY LẠI
        </a>
        <h2 class="fw-bold mt-2" style="color:#0f172a">Chỉnh sửa người dùng</h2>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius:20px; max-width:560px">
        <form method="POST" action="index.php?page=user-update&id=<?= $user['id'] ?>">

            <div class="mb-3">
                <label class="fw-bold small text-muted">EMAIL</label>
                <input type="email" name="email" class="form-control rounded-pill px-3"
                       value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-muted">ROLE</label>
                <select name="role" class="form-select rounded-pill px-3">
                    <option value="student"  <?= $user['role'] === 'student'  ? 'selected' : '' ?>>Student</option>
                    <option value="lecturer" <?= $user['role'] === 'lecturer' ? 'selected' : '' ?>>Lecturer</option>
                    <option value="admin"    <?= $user['role'] === 'admin'    ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="fw-bold small text-muted">TRẠNG THÁI</label>
                <select name="status" class="form-select rounded-pill px-3">
                    <option value="active" <?= ($user['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="locked" <?= ($user['status'] ?? '') === 'locked' ? 'selected' : '' ?>>Locked</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold text-white shadow-sm">
                    Cập nhật
                </button>
                <a href="index.php?page=users" class="btn btn-light rounded-pill px-4">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
