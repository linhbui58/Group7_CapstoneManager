<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc;">
    <h2 class="fw-bold mb-4">Tạo Phân Công Mới</h2>
    
    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 700px;">
        <form action="index.php?page=assignment-store" method="POST">
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
                    <?php foreach($lecturers as $l): ?>
                        <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow">Lưu Phân Công</button>
                <a href="index.php?page=assignments" class="btn btn-light rounded-pill px-4 ms-2">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>