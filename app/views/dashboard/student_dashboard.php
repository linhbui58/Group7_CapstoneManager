<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- ══ HERO BANNER ══ -->
    <div class="dash-hero">
        <div class="dash-hero-left">
            <?php
                $hour   = (int)date('H');
                $greet  = $hour < 12 ? 'Chào buổi sáng' : ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
                $uname  = htmlspecialchars($student['full_name'] ?? explode('@', ($_SESSION['user']['email'] ?? ''))[0]);
            ?>
            <div class="dash-greeting"><?= $greet ?> 👋</div>
            <div class="dash-username"><?= $uname ?></div>
            <div class="dash-meta" style="margin-top: 4px; font-size: 15px; color: #cbd5e1; font-weight: 500; letter-spacing: 0.5px;">
                <i class="fa-solid fa-id-card me-1"></i> <?= htmlspecialchars($student['student_code'] ?? 'Chưa cập nhật') ?>
            </div>
            <div class="dash-meta" style="margin-top: 8px;">
                <i class="fa-solid fa-graduation-cap me-1"></i> Khoa/Viện: <?= htmlspecialchars($student['faculty'] ?: 'Chưa cập nhật') ?> &nbsp;·&nbsp;
                <i class="fa-solid fa-phone me-1"></i> <?= htmlspecialchars($student['phone'] ?: 'Chưa cập nhật') ?>
            </div>
            <div class="dash-meta" style="margin-top: 8px;">
                <?= date('l, d/m/Y') ?> &nbsp;·&nbsp;
                <span>Sinh viên</span>
            </div>
        </div>
        <div class="dash-hero-right">
            <a href="index.php?page=topic-create" class="btn-hero btn-hero-primary">
                <i class="fa-solid fa-book-open"></i> Đề tài
            </a>
            <a href="index.php?page=submission-create" class="btn-hero btn-hero-accent">
                <i class="fa-solid fa-upload"></i> Bài nộp
            </a>
        </div>
    </div>

    <!-- ══ KPI GRID ══ -->
    <?php
        $pct      = $progress['progress_percentage'] ?? 0;
        $avgScore = number_format($progress['average_score'] ?? 0, 1);
        $totalMs  = $progress['total_milestones']    ?? 0;
        $doneMs   = $progress['total_submissions']   ?? 0;
    ?>
    <div class="kpi-grid" style="grid-template-columns: repeat(3,1fr)">

        <!-- Đề tài đã đăng ký -->
        <div class="kpi-card blue" style="grid-column: span 1; flex-direction: column; gap: 12px; align-items: flex-start;">
            <div style="display:flex; align-items:center; gap:14px; width:100%">
                <div class="kpi-icon"><i class="fa-solid fa-book-open"></i></div>
                <div class="kpi-body">
                    <div class="kpi-label">Đề tài đăng ký</div>
                    <div style="font-size:15px; font-weight:700; color:#0f172a; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px;"
                         title="<?= htmlspecialchars($registeredTopic['title'] ?? 'Chưa đăng ký') ?>">
                        <?= $registeredTopic ? htmlspecialchars($registeredTopic['title']) : '<span style="color:#94a3b8">Chưa đăng ký</span>' ?>
                    </div>
                </div>
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
                        <div class="workload-bar-fill <?= $pct >= 80 ? 'workload-ok' : ($pct >= 40 ? 'workload-warn' : 'workload-full') ?>"
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
                <div class="kpi-value"><?= $avgScore ?></div>
                <div class="kpi-sub">
                    <span class="kpi-badge <?= (float)$avgScore >= 7 ? 'up' : ((float)$avgScore >= 5 ? 'neu' : 'down') ?>">
                        <i class="fa-solid fa-<?= (float)$avgScore >= 7 ? 'arrow-up' : ((float)$avgScore >= 5 ? 'minus' : 'arrow-down') ?>"></i>
                        <?= (float)$avgScore >= 7 ? 'Tốt' : ((float)$avgScore >= 5 ? 'Trung bình' : 'Cần cải thiện') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ MILESTONES TABLE ══ -->
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
                    <tr>
                        <th>Milestone</th>
                        <th>Deadline</th>
                        <th>Còn lại</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($upcomingMilestones)): ?>
                        <?php foreach($upcomingMilestones as $ms): ?>
                            <?php
                                $diff = strtotime($ms['deadline']) - time();
                                $days = max(0, floor($diff / 86400));
                                $cls  = $days < 3 ? 'urgent' : ($days < 7 ? 'warning' : 'ok');
                                $ico  = $days < 3 ? 'circle-exclamation' : ($days < 7 ? 'triangle-exclamation' : 'circle-check');
                            ?>
                            <tr>
                                <td class="fw-semibold text-uppercase"><?= htmlspecialchars($ms['title']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($ms['deadline'])) ?></td>
                                <td>
                                    <span class="deadline-badge <?= $cls ?>">
                                        <i class="fa-solid fa-<?= $ico ?>"></i>
                                        <?= $days ?> ngày
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

<?php require '../app/views/layouts/footer.php'; ?>
