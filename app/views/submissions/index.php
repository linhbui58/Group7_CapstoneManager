<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
.sub-topbar {
    background:#fff; border-radius:20px; padding:22px 28px;
    display:flex; justify-content:space-between; align-items:center;
    box-shadow:0 4px 20px rgba(0,0,0,.05); margin-bottom:24px;
    border:1px solid #f1f5f9;
}
.sub-topbar h2 { font-size:20px; font-weight:800; color:#0f172a; margin:0 0 4px; }
.sub-topbar p  { font-size:13px; color:#64748b; margin:0; }

.sub-stat-card {
    background:#fff; border-radius:18px; padding:18px 20px;
    display:flex; align-items:center; gap:14px;
    box-shadow:0 4px 16px rgba(0,0,0,.05); border:1px solid #f1f5f9;
}
.sub-stat-icon { width:44px; height:44px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:17px; flex-shrink:0; }
.sub-stat-label { font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.5px; }
.sub-stat-val   { font-size:22px; font-weight:800; color:#0f172a; line-height:1.1; }

.sub-card { background:#fff; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,.06); border:1px solid #f1f5f9; overflow:hidden; }

.s-pill { display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; letter-spacing:.4px; text-transform:uppercase; padding:4px 12px; border-radius:50px; }
.s-submitted { background:#eff6ff; color:#2563eb; }
.s-late      { background:#fef2f2; color:#dc2626; }
.s-revision  { background:#fff7ed; color:#ea580c; }
.s-default   { background:#f1f5f9; color:#64748b; }

.sc-chip { display:inline-flex; align-items:center; gap:5px; font-weight:800; font-size:14px; padding:4px 12px; border-radius:50px; }
.sc-hi  { background:#f0fdf4; color:#16a34a; }
.sc-mid { background:#fff7ed; color:#ea580c; }
.sc-low { background:#fef2f2; color:#dc2626; }

.act-btn { width:32px; height:32px; border-radius:9px; display:inline-flex; align-items:center; justify-content:center; font-size:13px; border:none; text-decoration:none; transition:all .15s; }
.act-btn:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.12); }
.f-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }

/* Override DataTable để hòa hợp với design */
#submissionsTable thead th { font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.6px; background:#fafbff; border-bottom:1px solid #f1f5f9 !important; padding:13px 14px; }
#submissionsTable tbody td { vertical-align:middle; padding:13px 14px; border-color:#f8fafc; font-size:13px; }
#submissionsTable tbody tr:hover td { background:#f8faff; }
.dataTables_wrapper .dataTables_filter input { border:1.5px solid #e2e8f0; border-radius:10px; padding:6px 12px; font-size:13px; outline:none; }
.dataTables_wrapper .dataTables_filter input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.dataTables_wrapper .dataTables_length select { border:1.5px solid #e2e8f0; border-radius:8px; padding:4px 8px; font-size:13px; }
</style>

<div class="main-content" style="padding:28px 32px; background:#f4f7fe; min-height:100vh;">

    <!-- TOPBAR -->
    <div class="sub-topbar">
        <div>
            <h2><i class="fa-solid fa-file-arrow-up me-2" style="color:#6366f1"></i>Bài nộp Milestone</h2>
            <p><?= $_SESSION['user']['role']==='student' ? 'Bài nộp của bạn theo từng cột mốc đồ án' : 'Tổng hợp bài nộp của tất cả sinh viên' ?></p>
        </div>
        <?php if ($_SESSION['user']['role']==='student'): ?>
            <a href="index.php?page=submission-create"
               class="btn btn-primary rounded-pill px-4 fw-bold"
               style="background:linear-gradient(135deg,#6366f1,#4f46e5);border:none;">
                <i class="fa-solid fa-upload me-2"></i>Nộp bài mới
            </a>
        <?php endif; ?>
    </div>

    <!-- ALERTS -->
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

    <!-- STATS (admin/lecturer) -->
    <?php if ($_SESSION['user']['role']!=='student' && !empty($submissions)): ?>
        <?php
            $total    = count($submissions);
            $submitted= count(array_filter($submissions, fn($s)=>($s['status']??'')==='submitted'));
            $late     = count(array_filter($submissions, fn($s)=>($s['status']??'')==='late'));
            $revision = count(array_filter($submissions, fn($s)=>($s['status']??'')==='revision_required'));
        ?>
        <div class="row g-3 mb-4">
            <?php foreach([
                ['Tổng bài nộp',$total,    'fa-layer-group',   '#eff6ff','#2563eb'],
                ['Đã nộp',      $submitted,'fa-circle-check',  '#f0fdf4','#16a34a'],
                ['Nộp trễ',     $late,     'fa-clock',         '#fef2f2','#dc2626'],
                ['Cần sửa lại', $revision, 'fa-rotate-left',   '#fff7ed','#ea580c'],
            ] as [$lbl,$val,$ico,$bg,$clr]): ?>
            <div class="col-6 col-md-3">
                <div class="sub-stat-card">
                    <div class="sub-stat-icon" style="background:<?=$bg?>;color:<?=$clr?>"><i class="fa-solid <?=$ico?>"></i></div>
                    <div><div class="sub-stat-label"><?=$lbl?></div><div class="sub-stat-val"><?=$val?></div></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="sub-card">
        <div class="p-3">
            <table class="table table-hover mb-0" id="submissionsTable" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <?php if ($_SESSION['user']['role']!=='student'): ?><th>Sinh viên</th><?php endif; ?>
                        <th>Cột mốc</th>
                        <th>File</th>
                        <th>Trạng thái</th>
                        <?php if ($_SESSION['user']['role']==='student'): ?><th>Điểm</th><?php endif; ?>
                        <th>Thời gian nộp</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($submissions)): ?>
                    <?php foreach ($submissions as $sub): ?>
                        <?php
                            $status = $sub['status'] ?? 'submitted';
                            [$sCls,$sLbl,$sIco] = match($status) {
                                'submitted'         => ['s-submitted','Đã nộp','check-circle'],
                                'late'              => ['s-late','Nộp trễ','clock'],
                                'revision_required' => ['s-revision','Cần sửa','rotate-left'],
                                default             => ['s-default',ucfirst($status),'circle'],
                            };
                            $ext = strtolower(pathinfo($sub['file_path']??'', PATHINFO_EXTENSION));
                            [$fIco,$fClr] = match($ext) {
                                'pdf'        => ['fa-file-pdf','#ef4444'],
                                'doc','docx' => ['fa-file-word','#2563eb'],
                                'zip','rar'  => ['fa-file-zipper','#f59e0b'],
                                default      => ['fa-file','#64748b'],
                            };
                        ?>
                        <tr>
                            <td><span class="fw-bold text-muted">#<?= $sub['id'] ?></span></td>

                            <?php if ($_SESSION['user']['role']!=='student'): ?>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="f-icon" style="background:#f1f5f9;color:#6366f1"><i class="fa-solid fa-user-graduate"></i></div>
                                    <span class="fw-semibold"><?= htmlspecialchars($sub['student_name']??'—') ?></span>
                                </div>
                            </td>
                            <?php endif; ?>

                            <td><span class="fw-semibold text-uppercase"><?= htmlspecialchars($sub['milestone_title']??'—') ?></span></td>

                            <td>
                                <?php if (!empty($sub['file_path'])): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="f-icon" style="background:#f8fafc;color:<?=$fClr?>"><i class="fa-solid <?=$fIco?>"></i></div>
                                        <span class="text-muted text-truncate" style="max-width:120px"><?= htmlspecialchars(basename($sub['file_path'])) ?></span>
                                    </div>
                                <?php else: ?>—<?php endif; ?>
                            </td>

                            <td><span class="s-pill <?=$sCls?>"><i class="fa-solid fa-<?=$sIco?>"></i><?=$sLbl?></span></td>

                            <?php if ($_SESSION['user']['role']==='student'): ?>
                            <td>
                                <?php if ($sub['score']!==null): ?>
                                    <?php $sv=(float)$sub['score']; $scCls=$sv>=8?'sc-hi':($sv>=5?'sc-mid':'sc-low'); ?>
                                    <span class="sc-chip <?=$scCls?>" title="<?= htmlspecialchars($sub['feedback']??'') ?>">
                                        <i class="fa-solid fa-star" style="font-size:10px"></i><?=$sv?>/10
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size:12px">Chưa chấm</span>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>

                            <td>
                                <div class="fw-semibold"><?= $sub['submitted_at'] ? date('d/m/Y',strtotime($sub['submitted_at'])) : '—' ?></div>
                                <div class="text-muted" style="font-size:11px"><?= $sub['submitted_at'] ? date('H:i',strtotime($sub['submitted_at'])) : '' ?></div>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="index.php?page=submission-show&id=<?=$sub['id']?>" class="act-btn" style="background:#f1f5f9;color:#64748b" title="Xem"><i class="fa-solid fa-eye"></i></a>
                                    <?php if (!empty($sub['file_path'])): ?>
                                        <a href="assets/uploads/submissions/<?= htmlspecialchars($sub['file_path']) ?>" target="_blank" class="act-btn text-white" style="background:#10b981" title="Tải file"><i class="fa-solid fa-download"></i></a>
                                    <?php endif; ?>
                                    <?php if (in_array($_SESSION['user']['role'],['admin','lecturer'])): ?>
                                        <a href="index.php?page=score-create&submission_id=<?=$sub['id']?>" class="act-btn text-white" style="background:#6366f1" title="Chấm điểm"><i class="fa-solid fa-star"></i></a>
                                        <?php if ($status!=='submitted'): ?>
                                            <form action="index.php?page=submission-status&id=<?=$sub['id']?>&status=submitted" method="POST" class="d-inline">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                                <button type="submit" class="act-btn text-white" style="background:#22c55e; border:none;" title="Xác nhận"><i class="fa-solid fa-check"></i></button>
                                            </form>
                                        <?php endif; ?>
                                        <form action="index.php?page=submission-status&id=<?=$sub['id']?>&status=revision_required" method="POST" class="d-inline" onsubmit="return confirm('Yêu cầu sửa lại?')">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                            <button type="submit" class="act-btn text-white" style="background:#f59e0b; border:none;" title="Yêu cầu sửa"><i class="fa-solid fa-rotate-left"></i></button>
                                        </form>
                                        <form action="index.php?page=submission-delete&id=<?=$sub['id']?>" method="POST" class="d-inline" onsubmit="return confirm('Xóa bài nộp này?')">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                            <button type="submit" class="act-btn text-white" style="background:#ef4444; border:none;" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fa-solid fa-inbox fa-3x text-muted d-block mb-3" style="opacity:.3"></i>
                            <p class="fw-semibold text-muted mb-1">Chưa có bài nộp nào</p>
                            <?php if ($_SESSION['user']['role']==='student'): ?>
                                <p class="text-muted small">Bấm <strong>Nộp bài mới</strong> để bắt đầu</p>
                            <?php endif; ?>
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
            search: '', searchPlaceholder: 'Tìm kiếm bài nộp...',
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
