<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<?php
// Build chart data — fill missing months with 0
function buildMonthSeries(array $rows, int $months = 6): array {
    $map = [];
    foreach ($rows as $r) $map[$r['ym']] = (int)$r['cnt'];
    $labels = $data = [];
    $viMonth = ['Th1','Th2','Th3','Th4','Th5','Th6','Th7','Th8','Th9','Th10','Th11','Th12'];
    for ($i = $months - 1; $i >= 0; $i--) {
        $ym = date('Y-m', strtotime("-$i month"));
        $m  = (int)date('m', strtotime("-$i month"));
        $y  = date('Y', strtotime("-$i month"));
        $labels[] = $viMonth[$m - 1] . ' ' . $y;
        $data[]   = $map[$ym] ?? 0;
    }
    return ['labels' => $labels, 'data' => $data];
}
$subChart   = buildMonthSeries($submissionsByMonth ?? []);
$topicChart = buildMonthSeries($topicsByMonth ?? []);
?>

<div class="main-content">

    <!-- ══ HERO ══ -->
    <div class="dash-hero">
        <div class="dash-hero-left">
            <?php
                $hour  = (int)date('H');
                $greet = $hour < 12 ? 'Chào buổi sáng' : ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
                $uname = htmlspecialchars(explode('@', $_SESSION['user']['email'] ?? '')[0]);
            ?>
            <div class="dash-greeting"><?= $greet ?> 👋</div>
            <div class="dash-username"><?= $uname ?></div>
            <div class="dash-meta"><?= date('l, d/m/Y') ?> &nbsp;·&nbsp; <span>Quản trị viên</span></div>
        </div>
        <div class="dash-hero-right">
            <a href="index.php?page=topic-create" class="btn-hero btn-hero-primary"><i class="fa-solid fa-plus"></i> Đề tài mới</a>
            <a href="index.php?page=students"     class="btn-hero btn-hero-primary"><i class="fa-solid fa-user-graduate"></i> Sinh viên</a>
            <a href="index.php?page=lecturers"    class="btn-hero btn-hero-accent"><i class="fa-solid fa-chalkboard-user"></i> Giảng viên</a>
        </div>
    </div>

    <!-- ══ KPI ══ -->
    <div class="kpi-grid">
        <div class="kpi-card blue">
            <div class="kpi-icon"><i class="fa-solid fa-book"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Tổng đề tài</div>
                <div class="kpi-value"><?= $totalTopics ?? 0 ?></div>
                <div class="kpi-sub"><span class="kpi-badge neu"><i class="fa-solid fa-circle-check"></i> <?= $approvedCount ?> đã duyệt</span></div>
            </div>
        </div>
        <div class="kpi-card green">
            <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Sinh viên</div>
                <div class="kpi-value"><?= $totalStudents ?? 0 ?></div>
                <div class="kpi-sub"><span class="kpi-badge up"><i class="fa-solid fa-arrow-up"></i> Đang hoạt động</span></div>
            </div>
        </div>
        <div class="kpi-card amber">
            <div class="kpi-icon"><i class="fa-solid fa-user-tie"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Giảng viên</div>
                <div class="kpi-value"><?= $totalLecturers ?? 0 ?></div>
                <div class="kpi-sub"><span class="kpi-badge neu"><i class="fa-solid fa-chalkboard"></i> Hướng dẫn</span></div>
            </div>
        </div>
        <div class="kpi-card rose">
            <div class="kpi-icon"><i class="fa-solid fa-file-import"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Bài nộp</div>
                <div class="kpi-value"><?= $totalSubmissions ?? 0 ?></div>
                <div class="kpi-sub"><span class="kpi-badge down"><i class="fa-solid fa-clock"></i> <?= $pendingCount ?> chờ duyệt</span></div>
            </div>
        </div>
    </div>

    <!-- ══ CHARTS ROW ══ -->
    <div class="row g-4 mb-4">

        <!-- Donut: trạng thái đề tài -->
        <div class="col-lg-4">
            <div class="dash-box h-100">
                <div class="dash-box-header">
                    <div class="dash-box-title"><span class="title-dot" style="background:#6366f1"></span>Trạng thái đề tài</div>
                </div>
                <div style="position:relative;height:220px;display:flex;align-items:center;justify-content:center">
                    <canvas id="donutChart"></canvas>
                </div>
                <div class="chart-legend mt-3">
                    <div class="legend-item"><span class="legend-dot" style="background:#94a3b8"></span>Bản nháp <strong><?= $draftCount ?></strong></div>
                    <div class="legend-item"><span class="legend-dot" style="background:#fbbf24"></span>Chờ duyệt <strong><?= $pendingCount ?></strong></div>
                    <div class="legend-item"><span class="legend-dot" style="background:#34d399"></span>Đã duyệt <strong><?= $approvedCount ?></strong></div>
                    <div class="legend-item"><span class="legend-dot" style="background:#f87171"></span>Từ chối <strong><?= $rejectedCount ?></strong></div>
                </div>
            </div>
        </div>

        <!-- Area: bài nộp theo tháng -->
        <div class="col-lg-8">
            <div class="dash-box h-100">
                <div class="dash-box-header">
                    <div class="dash-box-title"><span class="title-dot" style="background:#0ea5e9"></span>Bài nộp &amp; Đề tài mới (6 tháng)</div>
                </div>
                <div style="position:relative;height:250px">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ TABLES ROW ══ -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="dash-box">
                <div class="dash-box-header">
                    <div class="dash-box-title"><span class="title-dot" style="background:#0ea5e9"></span>Đề tài gần đây</div>
                    <a href="index.php?page=topics" class="btn btn-light btn-sm">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>#</th><th>Tiêu đề</th><th>Trạng thái</th></tr></thead>
                        <tbody>
                        <?php if(!empty($recentTopics)): foreach($recentTopics as $t): ?>
                            <tr>
                                <td class="fw-bold text-muted" style="width:40px">#<?= $t['id'] ?></td>
                                <td class="fw-semibold" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($t['title']) ?></td>
                                <td><span class="badge badge-<?= $t['status'] ?>"><?= ucfirst($t['status']) ?></span></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="3" class="text-center text-muted py-4">Chưa có đề tài</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="dash-box">
                <div class="dash-box-header">
                    <div class="dash-box-title"><span class="title-dot" style="background:#10b981"></span>Bài nộp gần đây</div>
                    <a href="index.php?page=submissions" class="btn btn-light btn-sm">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>#</th><th>Sinh viên</th><th>Trạng thái</th></tr></thead>
                        <tbody>
                        <?php if(!empty($recentSubmissions)): foreach($recentSubmissions as $s): ?>
                            <tr>
                                <td class="fw-bold text-muted" style="width:40px">#<?= $s['id'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($s['student_name']) ?></td>
                                <td><span class="badge badge-<?= $s['status'] ?>"><?= ucfirst($s['status']) ?></span></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="3" class="text-center text-muted py-4">Chưa có bài nộp</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.chart-legend { display:flex; flex-wrap:wrap; gap:10px 20px; padding: 0 4px; }
.legend-item  { display:flex; align-items:center; gap:6px; font-size:13px; color:#475569; }
.legend-item strong { color:#0f172a; }
.legend-dot   { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
</style>

<script>
// ── Donut chart ──
(function(){
    const ctx = document.getElementById('donutChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Bản nháp','Chờ duyệt','Đã duyệt','Từ chối'],
            datasets: [{
                data: [<?= $draftCount ?>, <?= $pendingCount ?>, <?= $approvedCount ?>, <?= $rejectedCount ?>],
                backgroundColor: ['#94a3b8','#fbbf24','#34d399','#f87171'],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 6,
            }]
        },
        options: {
            cutout: '72%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: c => '  ' + c.label + ': ' + c.parsed + ' đề tài'
                    },
                    backgroundColor: '#0f172a',
                    titleColor: '#94a3b8',
                    bodyColor: '#f8fafc',
                    padding: 12,
                    cornerRadius: 10,
                }
            }
        },
        plugins: [{
            id: 'centerText',
            afterDraw(chart) {
                const { ctx, chartArea: { left, top, right, bottom } } = chart;
                const cx = (left + right) / 2, cy = (top + bottom) / 2;
                const total = chart.data.datasets[0].data.reduce((a,b) => a+b, 0);
                ctx.save();
                ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                ctx.fillStyle = '#0f172a'; ctx.font = 'bold 26px Inter, sans-serif';
                ctx.fillText(total, cx, cy - 8);
                ctx.fillStyle = '#94a3b8'; ctx.font = '11px Inter, sans-serif';
                ctx.fillText('đề tài', cx, cy + 12);
                ctx.restore();
            }
        }]
    });
})();

// ── Trend / Area chart ──
(function(){
    const ctx = document.getElementById('trendChart');
    if (!ctx) return;
    const labels = <?= json_encode($subChart['labels']) ?>;
    const subData   = <?= json_encode($subChart['data']) ?>;
    const topicData = <?= json_encode($topicChart['data']) ?>;

    function makeGradient(ctx, color) {
        const g = ctx.createLinearGradient(0, 0, 0, 250);
        g.addColorStop(0, color.replace(')',', 0.18)').replace('rgb','rgba'));
        g.addColorStop(1, color.replace(')',', 0.00)').replace('rgb','rgba'));
        return g;
    }

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Bài nộp',
                    data: subData,
                    borderColor: '#6366f1',
                    backgroundColor: (ctx2) => makeGradient(ctx2.chart.ctx, 'rgb(99,102,241)'),
                    fill: true,
                    tension: 0.45,
                    pointBackgroundColor: '#6366f1',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 2.5,
                },
                {
                    label: 'Đề tài mới',
                    data: topicData,
                    borderColor: '#10b981',
                    backgroundColor: (ctx2) => makeGradient(ctx2.chart.ctx, 'rgb(16,185,129)'),
                    fill: true,
                    tension: 0.45,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 2.5,
                    borderDash: [5, 3],
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: { boxWidth: 10, boxHeight: 10, borderRadius: 3, useBorderRadius: true, font: { size: 12, weight: '600' }, color: '#475569', padding: 16 }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#94a3b8',
                    bodyColor: '#f8fafc',
                    padding: 14,
                    cornerRadius: 10,
                    callbacks: { label: c => '  ' + c.dataset.label + ': ' + c.parsed.y }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#64748b', font: { weight: '600', size: 12 } } },
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { stepSize: 1, color: '#64748b', font: { weight: '600', size: 12 } } }
            }
        }
    });
})();
</script>

<?php require '../app/views/layouts/footer.php'; ?>
