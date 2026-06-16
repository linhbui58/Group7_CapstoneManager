<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding:32px; background:#f4f7fe; min-height:100vh;">
    <div class="mb-4">
        <a href="index.php?page=students" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> QUAY LẠI
        </a>
        <h2 class="fw-bold mt-2" style="color:#0f172a">Chi tiết sinh viên</h2>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:24px; max-width:600px; overflow:hidden">
        <div class="card-body p-0">
            <?php
                $rows = [
                    ['ID',           '#' . $student['id']],
                    ['Mã sinh viên', $student['student_code'] ?? '—'],
                    ['Họ và tên',    $student['full_name']],
                    ['Email',        $student['email'] ?? '—'],
                    ['Số điện thoại',$student['phone'] ?? '—'],
                ];
            ?>
            <?php foreach ($rows as [$label, $value]): ?>
                <div class="d-flex" style="border-bottom:1px solid #f1f5f9">
                    <div class="fw-bold small text-muted bg-light px-4 py-3"
                         style="min-width:180px;text-transform:uppercase;letter-spacing:.5px;font-size:11px">
                        <?= $label ?>
                    </div>
                    <div class="px-4 py-3 fw-semibold" style="color:#1e293b">
                        <?= htmlspecialchars($value) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="card-footer bg-white p-4 border-0 d-flex gap-2">
            <a href="index.php?page=student-edit&id=<?= $student['id'] ?>"
               class="btn btn-warning rounded-pill px-4 fw-bold text-white shadow-sm">
                <i class="fa-solid fa-pen-to-square me-1"></i> Chỉnh sửa
            </a>
            <a href="index.php?page=students" class="btn btn-light rounded-pill px-4">Quay lại</a>
        </div>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
