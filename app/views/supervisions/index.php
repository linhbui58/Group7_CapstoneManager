<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe; min-height: 100vh;">

    <!-- ── Page Header ── -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Phân Công Giảng Viên</h2>
            <p class="text-muted small mb-0">Quản lý phân công giảng viên hướng dẫn cho sinh viên.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="index.php?page=supervision-create" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="fa fa-plus me-1"></i> Thêm phân công
            </a>
        </div>
    </div>

    <!-- ── Flash Messages ── -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- ── Content ── -->
    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="supervisionsTable">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th class="ps-2">Sinh Viên</th>
                            <th>Giảng Viên Hướng Dẫn</th>
                            <th>Học Kỳ</th>
                            <th>Người Phân Công</th>
                            <th class="text-center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($assignments)): foreach ($assignments as $a): ?>
                            <tr>
                                <td class="ps-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                             style="width:36px; height:36px; flex-shrink:0;">
                                            <i class="fa fa-user-graduate text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($a['student_name']) ?></div>
                                            <div class="text-muted" style="font-size:11px;">
                                                <?= htmlspecialchars($a['student_code']) ?> | Khoa: <?= htmlspecialchars($a['student_faculty']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center"
                                             style="width:36px; height:36px; flex-shrink:0;">
                                            <i class="fa fa-chalkboard-teacher text-info"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($a['lecturer_name']) ?></div>
                                            <div class="text-muted" style="font-size:11px;">
                                                <?= htmlspecialchars($a['lecturer_code']) ?> | Khoa: <?= htmlspecialchars($a['lecturer_faculty']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border" style="font-size:12px;">
                                        <i class="fa fa-calendar-alt me-1 text-muted"></i>
                                        <?= htmlspecialchars($a['semester_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="small text-muted"><?= htmlspecialchars($a['assigned_by_name'] ?? 'N/A') ?></span>
                                    <div style="font-size: 10px;" class="text-muted"><?= htmlspecialchars($a['assigned_at']) ?></div>
                                </td>
                                <td class="text-center">
                                    <form action="index.php?page=supervision-delete&id=<?= $a['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa phân công này?');">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle border-0" data-bs-toggle="tooltip" title="Xóa" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa fa-users fa-2x mb-3 d-block opacity-25"></i>
                                    <div class="fw-semibold">Chưa có phân công nào</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
    $('#supervisionsTable').DataTable({
        language: {
            search: '',
            searchPlaceholder: 'Tìm kiếm...',
            emptyTable: 'Không có dữ liệu.',
            lengthMenu: 'Hiển thị _MENU_ dòng',
            info: 'Hiển thị _START_ – _END_ / _TOTAL_',
            paginate: {
                previous: '<i class="fa fa-chevron-left"></i>',
                next: '<i class="fa fa-chevron-right"></i>'
            }
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small text-muted"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
