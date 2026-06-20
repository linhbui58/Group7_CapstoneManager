<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe;">
    <h2 class="fw-bold mb-4">Cập nhật đánh giá</h2>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 600px;">
        <form action="index.php?page=score-update&id=<?= $score['id'] ?>" method="POST"> <?= csrfField() ?>
            <div class="mb-3">
                <label class="fw-bold small text-muted">HỌ VÀ TÊN SINH VIÊN</label>
                <input type="text" class="form-control rounded-pill bg-light" value="<?= htmlspecialchars($score['student_name']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-muted">ĐIỂM HIỆN TẠI</label>
                <input type="number" step="0.1" min="0" max="10" name="score" class="form-control rounded-pill" value="<?= $score['score'] ?>" required>
            </div>

            <div class="mb-4">
                <label class="fw-bold small text-muted">NHẬN XÉT</label>
                <textarea name="feedback" class="form-control" rows="4" style="border-radius: 15px;"><?= htmlspecialchars($score['feedback']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold text-white shadow-sm">Lưu điểm</button>
            <a href="index.php?page=scores" class="btn btn-light rounded-pill px-4 ms-2">Hủy</a>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>