<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
    .sub-card {
        border-radius: 1.5rem;
        border: none;
        box-shadow: 0 8px 30px rgba(99, 102, 241, 0.07);
        background: #fff;
    }
    .file-icon-wrap {
        width: 42px; height: 42px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .status-pill {
        font-size: 11px; font-weight: 700;
        letter-spacing: 0.5px; text-transform: uppercase;
        padding: 4px 14px; border-radius: 50px;
    }
    .status-submitted { background: #eff6ff; color: #2563eb; }
    .status-reviewed  { background: #f0fdf4; color: #16a34a; }
    .status-rejected  { background: #fef2f2; color: #dc2626; }
    .status-default   { background: #f1f5f9; color: #64748b; }
    .action-btn {
        width: 34px; height: 34px; border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.85rem; border: none; transition: all .15s;
    }
    .action-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }
    .topbar-card {
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        background: #fff;
        box-shadow: 0 2px 12px rgba(0,0,0,.04);
    }
    thead th {
        font-size: 11px; font-weight: 700;
        letter-spacing: 0.6px; text-transform: uppercase;
        color: #94a3b8; border-bottom: 1px solid #f1f5f9 !important;
        padding-top: 14px; padding-bottom: 14px;
    }
    tbody tr { transition: background .12s; }
    tbody tr:hover { background: #f8faff; }
    tbody td { vertical-align: middle; border-color: #f8fafc; }
</style>

<div class="main-content" style="padding: 32px; background: #f4f7fe; min-height: 100vh;">

    <!-- TOPBAR -->
    <div class="topbar-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0f172a; font-size: 22px;">
                <i class="fa-solid fa-file-arrow-up me-2 text-primary"></i>Milestone Submissions
            </h2>
            <p class="text-muted small mb-0">
                <?php if ($_SESSION['user']['role'] === 'student'): ?>
                    Bài nộp của bạn theo từng cột mốc đồ án
                <?php else: ?>
                    Tổng hợp bài nộp của tất cả sinh viên
                <?php endif; ?>
            </p>
        </div>
        <?php if ($_SESSION['user']['role'] === 'student'): ?>
            <a href="index.php?page=submission-create"
               class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"
               style="background: linear-gradient(135deg,#6366f1,#4f46e5); border: none;">
                <i class="fa-solid fa-upload me-2"></i>Nộp bài mới
            </a>
        <?php endif; ?>
    </div>

    <!-- ALERTS -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- STATS ROW (chỉ hiện cho admin/lecturer) -->
    <?php if ($_SESSION['user']['role'] !== 'student' && !empty($submissions)): ?>
        <?php
            $total      = count($submissions);
            $submitted  = count(array_filter($submissions, fn($s) => ($s['status'] ?? '') === 'submitted'));
            $reviewed   = count(array_filter($submissions, fn($s) => ($s['status'] ?? '') === 'reviewed'));
            $rejected   = count(array_filter($submissions, fn($s) => ($s['status'] ?? '') === 'rejected'));
        ?>
        <div class="row g-3 mb-4">
            <?php foreach ([
                ['label'=>'Tổng bài nộp',  'val'=>$total,     'icon'=>'fa-layer-group',    'bg'=>'#eff6ff','color'=>'#2563eb'],
                ['label'=>'Chờ duyệt',      'val'=>$submitted, 'icon'=>'fa-hourglass-half', 'bg'=>'#fff7ed','color'=>'#ea580c'],
                ['label'=>'Đã duyệt',       'val'=>$reviewed,  'icon'=>'fa-circle-check',   'bg'=>'#f0fdf4','color'=>'#16a34a'],
                ['label'=>'Từ chối',        'val'=>$rejected,  'icon'=>'fa-circle-xmark',   'bg'=>'#fef2f2','color'=>'#dc2626'],
            ] as $stat): ?>
            <div class="col-6 col-md-3">
                <div class="sub-card p-3 d-flex align-items-center gap-3">
                    <div class="file-icon-wrap" style="background:<?= $stat['bg'] ?>;color:<?= $stat['color'] ?>">
                        <i class="fa-solid <?= $stat['icon'] ?>"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px"><?= $stat['label'] ?></div>
                        <div class="fw-bold" style="font-size:22px;color:#0f172a;line-height:1.2"><?= $stat['val'] ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="sub-card">
        <div class="table-responsive p-3">
            <table class="table table-hover mb-0" id="submissionsTable">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Sinh viên</th>
                        <th>Cột mốc</th>
                        <th>File</th>
                        <th>Trạng thái</th>
                        <th>Thời gian nộp</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($submissions)): ?>
                    <?php foreach ($submissions as $sub): ?>
                        <?php
                            $status = $sub['status'] ?? 'submitted';
                            $statusClass = match($status) {
                                'submitted'          => 'status-submitted',
                                'late'               => 'status-rejected',
                                'revision_required'  => 'status-rejected',
                                default              => 'status-default',
                            };
                            $statusLabel = match($status) {
                                'submitted'          => 'Đã nộp',
                                'late'               => 'Nộp trễ',
                                'revision_required'  => 'Cần sửa lại',
                                default              => ucfirst($status),
                            };
                            $ext = strtolower(pathinfo($sub['file_path'] ?? '', PATHINFO_EXTENSION));
                            $fileIcon = match($ext) {
                                'pdf'  => ['fa-file-pdf',  '#ef4444'],
                                'doc','docx' => ['fa-file-word', '#2563eb'],
                                'zip','rar'  => ['fa-file-zipper','#f59e0b'],
                                default => ['fa-file',     '#64748b'],
                            };
                        ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-muted small">#<?= $sub['id'] ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="file-icon-wrap" style="background:#f1f5f9;color:#6366f1;width:36px;height:36px;font-size:.9rem">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </div>
                                    <span class="fw-semibold" style="color:#1e293b">
                                        <?= htmlspecialchars($sub['student_name'] ?? '—') ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold small" style="color:#334155">
                                    <?= htmlspecialchars(ucfirst($sub['milestone_title'] ?? '—')) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($sub['file_path'])): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="file-icon-wrap" style="background:#f8fafc;color:<?= $fileIcon[1] ?>;width:34px;height:34px;font-size:.9rem">
                                            <i class="fa-solid <?= $fileIcon[0] ?>"></i>
                                        </div>
                                        <span class="small text-muted text-truncate" style="max-width:130px">
                                            <?= htmlspecialchars(basename($sub['file_path'])) ?>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-pill <?= $statusClass ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold small" style="color:#334155">
                                    <?= $sub['submitted_at'] ? date('d/m/Y', strtotime($sub['submitted_at'])) : '—' ?>
                                </div>
                                <div class="text-muted" style="font-size:11px">
                                    <?= $sub['submitted_at'] ? date('H:i', strtotime($sub['submitted_at'])) : '' ?>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="index.php?page=submission-show&id=<?= $sub['id'] ?>"
                                       class="action-btn bg-light text-secondary"
                                       title="Xem chi tiết">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <?php if (!empty($sub['file_path'])): ?>
                                        <a href="assets/uploads/<?= htmlspecialchars($sub['file_path']) ?>"
                                           target="_blank"
                                           class="action-btn text-white"
                                           style="background:#10b981"
                                           title="Tải file">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (in_array($_SESSION['user']['role'], ['admin','lecturer'])): ?>
                                        <?php if ($status !== 'submitted'): ?>
                                            <a href="index.php?page=submission-status&id=<?= $sub['id'] ?>&status=submitted"
                                               class="action-btn text-white"
                                               style="background:#10b981"
                                               title="Xác nhận đã nộp">
                                                <i class="fa-solid fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="index.php?page=submission-status&id=<?= $sub['id'] ?>&status=revision_required"
                                           class="action-btn text-white"
                                           style="background:#f59e0b"
                                           title="Yêu cầu sửa lại"
                                           onclick="return confirm('Yêu cầu sinh viên sửa lại bài này?')">
                                            <i class="fa-solid fa-rotate-left"></i>
                                        </a>
                                        <a href="index.php?page=submission-delete&id=<?= $sub['id'] ?>"
                                           class="action-btn btn-delete text-white"
                                           style="background:#ef4444"
                                           title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div style="opacity:.4">
                                <i class="fa-solid fa-inbox fa-3x mb-3 d-block text-muted"></i>
                                <p class="fw-semibold text-muted mb-0">Chưa có bài nộp nào</p>
                                <?php if ($_SESSION['user']['role'] === 'student'): ?>
                                    <p class="text-muted small mt-1">Bấm "Nộp bài mới" để bắt đầu</p>
                                <?php endif; ?>
                            </div>
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
    $('#submissionsTable').DataTable({
        retrieve: true,
        pageLength: 10,
        language: {
            search: '',
            searchPlaceholder: 'Tìm kiếm bài nộp...',
            lengthMenu: '_MENU_ dòng/trang',
            info: 'Hiển thị _START_–_END_ / _TOTAL_',
            paginate: { previous: 'Trước', next: 'Sau' },
            emptyTable: 'Không có dữ liệu'
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3 px-2"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
