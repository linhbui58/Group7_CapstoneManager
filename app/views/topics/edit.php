<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px;">
    <h2 class="fw-bold mb-4">Chỉnh sửa Đề Tài</h2>
    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 800px;">
        <form action="index.php?page=topic-update&id=<?= $topic['id'] ?>" method="POST"> <?= csrfField() ?>
            <div class="mb-3">
                <label class="fw-bold small text-muted">TIÊU ĐỀ ĐỀ TÀI</label>
                <input type="text" name="title" class="form-control rounded-pill px-3" value="<?= htmlspecialchars($topic['title']) ?>" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="fw-bold small text-muted">HỌC KỲ</label>
                    <select name="semester_id" class="form-select rounded-pill px-3">
                        <?php foreach($semesters as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $topic['semester_id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <div class="col-md-6">
                    <label class="fw-bold small text-muted">TRẠNG THÁI</label>
                    <select name="status" class="form-select rounded-pill px-3">
                        <option value="pending" <?= $topic['status'] == 'pending' ? 'selected' : '' ?>>Chờ duyệt</option>
                        <option value="approved" <?= $topic['status'] == 'approved' ? 'selected' : '' ?>>Đã duyệt</option>
                        <option value="rejected" <?= $topic['status'] == 'rejected' ? 'selected' : '' ?>>Từ chối</option>
                    </select>
                </div>
                <?php else: ?>
                    <input type="hidden" name="status" value="<?= htmlspecialchars($topic['status']) ?>">
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label class="fw-bold small text-muted">MÔ TẢ</label>
                <textarea name="description" class="form-control" rows="4" style="border-radius: 15px;"><?= htmlspecialchars($topic['description']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold text-white shadow-sm">Cập nhật</button>
            <a href="index.php?page=topic-management&tab=topics" class="btn btn-light rounded-pill px-4 ms-2">Hủy</a>
        </form>
    </div>
</div>
<?php require '../app/views/layouts/footer.php'; ?>