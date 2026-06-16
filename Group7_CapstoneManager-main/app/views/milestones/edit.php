<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=milestones" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> HỦY THAY ĐỔI
        </a>
        <h2 class="fw-bold mt-2">Cập Nhật Cột Mốc</h2>
    </div>

    <div class="card border-0 shadow-sm p-5 mx-auto" style="border-radius: 25px; max-width: 600px;">
        <form action="index.php?page=milestone-update&id=<?= $milestone['id'] ?>" method="POST">
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">TÊN GIAI ĐOẠN</label>
                <select name="title" class="form-select rounded-pill px-3" style="height: 50px;" required>
                    <option value="proposal" <?= $milestone['title'] == 'proposal' ? 'selected' : '' ?>>Đề cương nghiên cứu</option>
                    <option value="midterm" <?= $milestone['title'] == 'midterm' ? 'selected' : '' ?>>Báo cáo tiến độ</option>
                    <option value="final" <?= $milestone['title'] == 'final' ? 'selected' : '' ?>>Báo cáo nghiệm thu</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">HỌC KỲ</label>
                <select name="semester_id" class="form-select rounded-pill px-3" style="height: 50px;" required>
                    <?php foreach($semesters as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $milestone['semester_id'] == $s['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold small text-muted">GIA HẠN THỜI GIAN (DEADLINE)</label>
                <input type="datetime-local" name="deadline" 
                       value="<?= date('Y-m-d\TH:i', strtotime($milestone['deadline'])) ?>" 
                       class="form-control rounded-pill px-3" style="height: 50px;" required>
            </div>

            <button type="submit" class="btn btn-dark rounded-pill w-100 py-3 fw-bold shadow">
                CẬP NHẬT THỜI HẠN
            </button>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>