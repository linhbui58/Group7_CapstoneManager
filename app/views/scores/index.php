<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
.sc-topbar {
    background:#fff; border-radius:20px; padding:22px 28px;
    display:flex; justify-content:space-between; align-items:center;
    box-shadow:0 4px 20px rgba(0,0,0,.05); margin-bottom:24px;
    border:1px solid #f1f5f9;
}
.sc-topbar h2 { font-size:20px; font-weight:800; color:#0f172a; margin:0 0 4px; }
.sc-topbar p  { font-size:13px; color:#64748b; margin:0; }
.sc-card { background:#fff; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,.06); border:1px solid #f1f5f9; overflow:hidden; }

/* Score badge */
.score-chip {
    width:48px; height:48px; border-radius:14px;
    display:flex; align-items:center; justify-content:center;
    font-weight:800; font-size:16px; flex-shrink:0;
}
.score-hi  { background:#dcfce7; color:#16a34a; }
.score-mid { background:#fff7ed; color:#ea580c; }
.score-low { background:#fee2e2; color:#dc2626; }

/* Avg stat cards */
.sc-stat { background:#fff; border-radius:16px; padding:16px 20px; border:1px solid #f1f5f9; box-shadow:0 2px 10px rgba(0,0,0,.04); text-align:center; }
.sc-stat-val { font-size:28px; font-weight:800; line-height:1; margin-bottom:4px; }
.sc-stat-lbl { font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.5px; }

/* Table */
#scoreTable thead th { font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.6px; background:#fafbff; border-bottom:1px solid #f1f5f9 !important; padding:13px 16px; }
#scoreTable tbody td { vertical-align:middle; padding:13px 16px; border-color:#f8fafc; font-size:13px; }
#scoreTable tbody tr:hover td { background:#f8faff; }
.act-btn { width:30px; height:30px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:12px; border:none; text-decoration:none; transition:all .15s; }
.act-btn:hover { transform:translateY(-1px); box-shadow:0 4px 10px rgba(0,0,0,.12); }
</style>

<div class="main-content" style="padding:28px 32px; background:#f4f7fe; min-height:100vh;">

    <!-- TOPBAR -->
    <div class="sc-topbar">
        <div>
            <h2><i class="fa-solid fa-star me-2" style="color:#f59e0b"></i>Kết quả đánh giá</h2>
            <p>Theo dõi và quản lý điểm số bảo vệ đồ án của sinh viên</p>
        </div>
        <?php if ($_SESSION['user']['role'] === 'lecturer'): ?>
            <a href="index.php?page=score-create" class="btn btn-primary rounded-pill px-4 fw-bold"
               style="background:linear-gradient(135deg,#f59e0b,#d97706);border:none;">
                <i class="fa-solid fa-star me-2"></i>Chấm điểm mới
            </a>
        <?php endif; ?>
    </div>

    <!-- ALERTS -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- STATS ROW -->
    <?php if (!empty($scores)): ?>
    <?php
        $total = count($scores);
        $avg   = round(array_sum(array_column($scores, 'score')) / $total, 1);
        $hi    = count(array_filter($scores, fn($s) => $s['score'] >= 8));
        $lo    = count(array_filter($scores, fn($s) => $s['score'] < 5));
    ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="sc-stat">
                <div class="sc-stat-val" style="color:#6366f1"><?= $total ?></div>
                <div class="sc-stat-lbl">Tổng bài chấm</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="sc-stat">
                <div class="sc-stat-val" style="color:<?= $avg>=7?'#16a34a':($avg>=5?'#ea580c':'#dc2626') ?>"><?= $avg ?></div>
                <div class="sc-stat-lbl">Điểm trung bình</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="sc-stat">
                <div class="sc-stat-val" style="color:#16a34a"><?= $hi ?></div>
                <div class="sc-stat-lbl">Điểm giỏi (≥8)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="sc-stat">
                <div class="sc-stat-val" style="color:#dc2626"><?= $lo ?></div>
                <div class="sc-stat-lbl">Điểm yếu (<5)</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="sc-card">
        <div class="p-3">
            <table class="table table-hover mb-0" id="scoreTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Sinh viên & Đề tài</th>
                        <th class="text-center">Điểm</th>
                        <th>Giảng viên</th>
                        <th>Nhận xét</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($scores)): ?>
                    <?php foreach ($scores as $s): ?>
                        <?php
                            $sc = (float)$s['score'];
                            $scCls = $sc >= 8 ? 'score-hi' : ($sc >= 5 ? 'score-mid' : 'score-low');
                        ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($s['student_name']) ?></div>
                                <div class="text-muted text-truncate" style="font-size:12px;max-width:220px">
                                    <?= htmlspecialchars($s['topic_title'] ?? '—') ?>
                                </div>
                                <span style="font-size:11px;color:#94a3b8;text-transform:uppercase"><?= htmlspecialchars($s['milestone_title'] ?? '') ?></span>
                            </td>
                            <td class="text-center">
                                <div class="score-chip <?= $scCls ?> mx-auto">
                                    <?= number_format($sc, 1) ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($s['lecturer_name']) ?></div>
                                <div class="text-muted" style="font-size:11px"><?= date('d/m/Y', strtotime($s['graded_at'])) ?></div>
                            </td>
                            <td>
                                <span class="text-muted text-truncate d-block" style="max-width:180px;font-size:12px"
                                      title="<?= htmlspecialchars($s['feedback'] ?? '') ?>">
                                    <?= htmlspecialchars($s['feedback'] ?? '—') ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="index.php?page=score-edit&id=<?= $s['id'] ?>"
                                       class="act-btn text-white" style="background:#f59e0b" title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="index.php?page=score-delete&id=<?= $s['id'] ?>" method="POST"
                                          class="d-inline" onsubmit="return confirm('Xóa điểm này?')">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <button type="submit" class="act-btn text-white" style="background:#ef4444" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fa-solid fa-star fa-3x text-muted d-block mb-3" style="opacity:.3"></i>
                            <p class="fw-semibold text-muted mb-0">Chưa có kết quả nào</p>
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
    $('#scoreTable').DataTable({
        retrieve: true,
        pageLength: 10,
        language: {
            search: '', searchPlaceholder: 'Tìm kiếm kết quả...',
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
