<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
    .custom-card { border-radius:1.25rem; border:none; box-shadow:0 10px 30px rgba(160,174,192,.1); background:#fff; }
    .avatar-circle {
        width:38px; height:38px; border-radius:12px;
        background:linear-gradient(135deg,#6366f1,#a855f7);
        color:#fff; display:flex; align-items:center; justify-content:center;
        font-weight:700; font-size:.85rem; flex-shrink:0;
    }
    .btn-action {
        width:34px; height:34px; border-radius:10px;
        display:inline-flex; align-items:center; justify-content:center;
        font-size:.8rem; border:none; transition:all .15s; text-decoration:none;
    }
    .btn-action:hover { transform:translateY(-2px); box-shadow:0 4px 10px rgba(0,0,0,.1); }
</style>

<div class="main-content" style="padding:32px; background:#f4f7fe; min-height:100vh;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#0f172a">Students Management</h2>
            <p class="text-muted small mb-0">Quản lý danh sách sinh viên trong hệ thống</p>
        </div>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <a href="index.php?page=student-create"
               class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"
               style="background:linear-gradient(135deg,#6366f1,#4f46e5);border:none">
                <i class="fa-solid fa-plus me-2"></i>Thêm sinh viên
            </a>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
            <i class="fa-solid fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="custom-card">
        <div class="table-responsive p-3">
            <table class="table table-hover mb-0 datatable" id="studentTable">
                <thead>
                    <tr class="text-muted small" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px">
                        <th class="ps-4">#</th>
                        <th>Sinh viên</th>
                        <th>Mã SV</th>
                        <th>Số điện thoại</th>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <th class="text-end pe-4">Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $s): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted small">#<?= $s['id'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">
                                            <?= strtoupper(mb_substr($s['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="color:#1e293b"><?= htmlspecialchars($s['full_name']) ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($s['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-semibold small" style="color:#334155"><?= htmlspecialchars($s['student_code'] ?? '—') ?></td>
                                <td class="text-muted small"><?= htmlspecialchars($s['phone'] ?? '—') ?></td>
                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="index.php?page=student-show&id=<?= $s['id'] ?>"
                                               class="btn-action bg-info bg-opacity-10 text-info" title="Xem">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="index.php?page=student-edit&id=<?= $s['id'] ?>"
                                               class="btn-action bg-warning bg-opacity-10 text-warning" title="Sửa">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="index.php?page=student-delete&id=<?= $s['id'] ?>"
                                               class="btn-action btn-delete bg-danger bg-opacity-10 text-danger" title="Xóa">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-user-graduate fa-2x mb-2 d-block" style="opacity:.3"></i>
                                Chưa có sinh viên nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $.fn.dataTable.ext.errMode = 'none';
    if ($.fn.DataTable.isDataTable('#studentTable')) {
        $('#studentTable').DataTable().destroy();
    }
    $('#studentTable').DataTable({
        language: { search: '', searchPlaceholder: 'Tìm kiếm sinh viên...' },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
