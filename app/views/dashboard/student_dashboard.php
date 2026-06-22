<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<?php
    $hour     = (int)date('H');
    $greet    = $hour < 12 ? 'Chào buổi sáng' : ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
    $uname    = htmlspecialchars($student['full_name'] ?? explode('@', ($_SESSION['user']['email'] ?? ''))[0]);
    $pct      = $progress['progress_percentage'] ?? 0;
    $avgScore = (float)($progress['average_score'] ?? 0);
    $totalMs  = $progress['total_milestones']    ?? 0;
    $doneMs   = $progress['total_submissions']   ?? 0;
?>

<div class="main-content">

    <!-- ══ HERO ══ -->
    <div class="dash-hero">
        <div class="dash-hero-left">
            <div class="dash-greeting"><?= $greet ?> 👋</div>
            <div class="dash-username"><?= $uname ?></div>
            <div class="dash-meta" style="margin-top:8px">
                <i class="fa-solid fa-graduation-cap me-1"></i>
                Khoa/Viện: <?= htmlspecialchars($student['faculty'] ?? 'Chưa cập nhật') ?>
            </div>
            <div class="dash-meta" style="margin-top:8px">
                <?= date('l, d/m/Y') ?> &nbsp;·&nbsp; <span>Sinh viên</span>
            </div>
        </div>
        <div class="dash-hero-right">
            <a href="index.php?page=student-edit&id=<?= $_SESSION['user']['student_id'] ?? 0 ?>" class="btn-hero btn-hero-primary">
                <i class="fa-solid fa-pen"></i> Cập nhật profile
            </a>
            <a href="index.php?page=topic-create" class="btn-hero btn-hero-primary">
                <i class="fa-solid fa-book-open"></i> Đề tài
            </a>
            <a href="index.php?page=submission-create" class="btn-hero btn-hero-accent">
                <i class="fa-solid fa-upload"></i> Nộp bài
            </a>
        </div>
    </div>

    <!-- ══ KPI GRID ══ -->
    <div class="kpi-grid" style="grid-template-columns: repeat(3,1fr)">

        <!-- Đề tài đăng ký -->
        <div class="kpi-card blue">
            <div class="kpi-icon"><i class="fa-solid fa-book-open"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Đề tài đăng ký</div>
                <div style="font-size:15px;font-weight:700;color:#0f172a;margin-top:4px;
                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px"
                     title="<?= htmlspecialchars($registeredTopic['title'] ?? 'Chưa đăng ký') ?>">
                    <?= $registeredTopic
                        ? htmlspecialchars($registeredTopic['title'])
                        : '<span style="color:#94a3b8;font-size:13px">Chưa đăng ký</span>' ?>
                </div>
                <?php if ($registeredTopic): ?>
                    <?php
                        $ts = $registeredTopic['status'] ?? '';
                        $tc = match($ts) { 'approved'=>'up','pending'=>'neu','rejected'=>'down', default=>'neu' };
                        $tl = match($ts) { 'approved'=>'Đã duyệt','pending'=>'Chờ duyệt','rejected'=>'Từ chối', default=>$ts };
                        $ti = match($ts) { 'approved'=>'check','pending'=>'clock','rejected'=>'xmark', default=>'circle' };
                    ?>
                    <div class="kpi-sub mt-1">
                        <span class="kpi-badge <?= $tc ?>"><i class="fa-solid fa-<?= $ti ?>"></i> <?= $tl ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tiến độ -->
        <div class="kpi-card green">
            <div class="kpi-icon"><i class="fa-solid fa-bars-progress"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Tiến độ</div>
                <div class="kpi-value"><?= $pct ?>%</div>
                <div style="margin-top:8px">
                    <div class="workload-bar">
                        <div class="workload-bar-fill <?= $pct>=80?'workload-ok':($pct>=40?'workload-warn':'workload-full') ?>"
                             style="width:<?= $pct ?>%"></div>
                    </div>
                    <div class="kpi-sub"><?= $doneMs ?> / <?= $totalMs ?> Milestones</div>
                </div>
            </div>
        </div>

        <!-- Điểm trung bình -->
        <div class="kpi-card amber">
            <div class="kpi-icon"><i class="fa-solid fa-star"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Điểm trung bình</div>
                <div class="kpi-value"><?= number_format($avgScore,1) ?></div>
                <div class="kpi-sub">
                    <span class="kpi-badge <?= $avgScore>=7?'up':($avgScore>=5?'neu':'down') ?>">
                        <i class="fa-solid fa-<?= $avgScore>=7?'arrow-up':($avgScore>=5?'minus':'arrow-down') ?>"></i>
                        <?= $avgScore>=7?'Tốt':($avgScore>=5?'Trung bình':'Cần cải thiện') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ ROW: MILESTONE + ĐIỂM ══ -->
    <div class="row g-4">

        <!-- Milestone sắp đến hạn -->
        <div class="col-lg-6">
            <div class="dash-box">
                <div class="dash-box-header">
                    <div class="dash-box-title">
                        <span class="title-dot" style="background:#f43f5e"></span>
                        <i class="fa-solid fa-clock" style="color:#f43f5e;font-size:14px"></i>
                        Milestone sắp đến hạn
                    </div>
                    <a href="index.php?page=milestones" class="btn btn-light btn-sm">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>Milestone</th><th>Deadline</th><th>Còn lại</th></tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($upcomingMilestones)): ?>
                            <?php foreach ($upcomingMilestones as $ms): ?>
                                <?php
                                    $diff = strtotime($ms['deadline']) - time();
                                    $days = max(0, floor($diff / 86400));
                                    $cls  = $days < 3 ? 'urgent' : ($days < 7 ? 'warning' : 'ok');
                                    $ico  = $days < 3 ? 'circle-exclamation' : ($days < 7 ? 'triangle-exclamation' : 'circle-check');
                                ?>
                                <tr>
                                    <td class="fw-semibold text-uppercase"><?= htmlspecialchars($ms['title']) ?></td>
                                    <td class="text-muted" style="font-size:13px"><?= date('d/m/Y H:i', strtotime($ms['deadline'])) ?></td>
                                    <td>
                                        <span class="deadline-badge <?= $cls ?>">
                                            <i class="fa-solid fa-<?= $ico ?>"></i><?= $days ?> ngày
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted py-4">
                                <i class="fa-regular fa-calendar-check fa-lg mb-2 d-block text-success"></i>
                                Không có milestone sắp đến hạn
                            </td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Điểm bài nộp -->
        <div class="col-lg-6">
            <div class="dash-box">
                <div class="dash-box-header">
                    <div class="dash-box-title">
                        <span class="title-dot" style="background:#f59e0b"></span>
                        <i class="fa-solid fa-star" style="color:#f59e0b;font-size:14px"></i>
                        Điểm bài nộp của tôi
                    </div>
                    <a href="index.php?page=submissions" class="btn btn-light btn-sm">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>Milestone</th><th>Điểm</th><th>Nhận xét</th><th>Ngày chấm</th></tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($myScores)): ?>
                            <?php foreach ($myScores as $sc): ?>
                                <?php
                                    $sv = (float)$sc['score'];
                                    $badgeCls = $sv >= 8 ? 'up' : ($sv >= 5 ? 'neu' : 'down');
                                ?>
                                <tr>
                                    <td class="fw-semibold text-uppercase"><?= htmlspecialchars($sc['milestone']) ?></td>
                                    <td>
                                        <span class="kpi-badge <?= $badgeCls ?>" style="font-size:13px;padding:4px 10px">
                                            <i class="fa-solid fa-star" style="font-size:10px"></i>
                                            <?= $sv ?>/10
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size:12px;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                                        title="<?= htmlspecialchars($sc['feedback'] ?? '') ?>">
                                        <?= htmlspecialchars($sc['feedback'] ?? '—') ?>
                                    </td>
                                    <td class="text-muted" style="font-size:12px"><?= date('d/m/Y', strtotime($sc['graded_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">
                                <i class="fa-regular fa-star fa-lg mb-2 d-block text-warning"></i>
                                Chưa có bài nộp nào được chấm điểm
                            </td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div><!-- end row -->

</div>

<?php require '../app/views/layouts/footer.php'; ?>
