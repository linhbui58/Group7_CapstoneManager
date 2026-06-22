<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
.sv-topbar {
    background:#fff; border-radius:20px; padding:22px 28px;
    display:flex; justify-content:space-between; align-items:center;
    box-shadow:0 4px 20px rgba(0,0,0,.05); margin-bottom:24px;
    border:1px solid #f1f5f9;
}
.sv-topbar h2 { font-size:20px; font-weight:800; color:#0f172a; margin:0 0 4px; }
.sv-topbar p  { font-size:13px; color:#64748b; margin:0; }
.sv-card { background:#fff; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,.06); border:1px solid #f1f5f9; overflow:hidden; }
.sv-avatar { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
#supervisionsTable thead th { font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.6px; background:#fafbff; border-bottom:1px solid #f1f5f9 !important; padding:13px 16px; }
#supervisionsTable tbody td { vertical-align:middle; padding:13px 16px; border-color:#f8fafc; font-size:13px; }
#supervisionsTable tbody tr:hover td { background:#f8faff; }
.act-btn { width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:12px; border:none; text-decoration:none; transition:all .15s; }
.act-btn:hover { transform:translateY(-1px); box-shadow:0 4px 10px rgba(0,0,0,.12); }
</style>

<div class="main-content" style="padding:28px 32px; background:#f4f7fe; min-height:100vh;">

    <!-- TOPBAR -->
    <div class="sv-topbar">
        <div>
            <h2><i class="fa fa-user-tie me-2" style="color:#6366f1"></i>Phân Công Giảng Viên Hướng Dẫn</h2>
            <p>Quản lý phân công giảng viên hướng dẫn cho sinh viên theo học kỳ</p>
        </div>
        <a href="index.php?page=supervision-create" class="btn btn-primary rounded-pill px-4 fw-bold"
           style="background:linear-gradient(135deg,#6366f1,#4f46e5);border:none;">
            <i class="fa fa-plus me-2"></i>Thêm phân công
        </a>
    </div>

    <!-- ALERTS -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
            <i class="fa fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
            <i class="fa fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="sv-card">
        <div class="p-3">
            <table class="table table-hover mb-0" id="supervisionsTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Sinh viên</th>
                        <th>Giảng viên hướng dẫn</th>
                        <th>Học kỳ</th>
                        <th>Người phân công</th>
                        <th>Ngày phân công</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($assignments)): ?>
                    <?php foreach ($assignments as $a): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="sv-avatar" style="background:#eff6ff;color:#2563eb">
                                    <i class="fa fa-user-graduate"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($a['student_name']) ?></div>
                                    <div class="text-muted" style="font-size:11px"><?= htmlspecialchars($a['student_faculty'] ?? '—') ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="sv-avatar" style="background:#f0fdf4;color:#16a34a">
                                    <i class="fa fa-chalkboard-user"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($a['lecturer_name']) ?></div>
                                    <div class="text-muted" style="font-size:11px">Mã GV: <?= htmlspecialchars($a['lecturer_code']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background:#f1f5f9;color:#334155;font-size:12px;padding:5px 10px;border-radius:8px">
                                <i class="fa fa-calendar-alt me-1 text-muted"></i><?= htmlspecialchars($a['semester_name']) ?>
                            </span>
                        </td>
                        <td class="text-muted"><?= htmlspecialchars($a['assigned_by_name'] ?? 'Admin') ?></td>
                        <td class="text-muted"><?= date('d/m/Y', strtotime($a['assigned_at'])) ?></td>
                        <td class="text-end">
                            <form action="index.php?page=supervision-delete&id=<?= $a['id'] ?>" method="POST"
                                  class="d-inline" onsubmit="return confirm('Xác nhận xóa phân công này?')">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <button type="submit" class="act-btn text-white" style="background:#ef4444" title="Xóa">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fa fa-users fa-3x text-muted d-block mb-3" style="opacity:.3"></i>
                            <p class="fw-semibold text-muted mb-0">Chưa có phân công nào</p>
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
    $('#supervisionsTable').DataTable({
        retrieve: true,
        pageLength: 10,
        language: {
            search: '', searchPlaceholder: 'Tìm kiếm...',
            lengthMenu: '_MENU_ dòng/trang',
            info: 'Hiển thị _START_–_END_ / _TOTAL_',
            paginate: { previous: '← Trước', next: 'Sau →' },
            emptyTable: 'Không có dữ liệu'
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3 px-1"<"small text-muted"l><f>>rt<"d-flex justify-content-between align-items-center mt-3 px-1"<"small text-muted"i><p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
