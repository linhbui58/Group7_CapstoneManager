<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- ══ HERO BANNER ══ -->
    <div class="dash-hero">
        <div class="dash-hero-left">
            <?php
                $hour  = (int)date('H');
                $greet = $hour < 12 ? 'Chào buổi sáng' : ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
                $uname = htmlspecialchars($lecturer['full_name'] ?? explode('@', ($_SESSION['user']['email'] ?? ''))[0]);
            ?>
            <div class="dash-greeting"><?= $greet ?> 👋</div>
            <div class="dash-username"><?= $uname ?></div>
            <div class="dash-meta" style="margin-top: 8px;">
                <i class="fa-solid fa-graduation-cap me-1"></i> Khoa/Viện: <?= htmlspecialchars($lecturer['faculty'] ?? 'Chưa cập nhật') ?>
            </div>
            <div class="dash-meta" style="margin-top: 8px;">
                <?= date('l, d/m/Y') ?> &nbsp;·&nbsp;
                <span>Giảng viên</span>
            </div>
        </div>
        <div class="dash-hero-right">
            <a href="index.php?page=scores" class="btn-hero btn-hero-accent">
                <i class="fa-solid fa-star"></i> Chấm điểm
            </a>
        </div>
    </div>

    <!-- ══ KPI GRID ══ -->
    <?php
        $wl  = $workload ?? 0;
        $qt  = $quota    ?? 1;
        $pct = $qt > 0 ? round($wl / $qt * 100) : 0;
        $wlCls = $pct >= 90 ? 'workload-full' : ($pct >= 60 ? 'workload-warn' : 'workload-ok');
    ?>
    <div class="kpi-grid" style="grid-template-columns: repeat(3,1fr)">

        <div class="kpi-card blue">
            <div class="kpi-icon"><i class="fa-solid fa-book"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Đề tài phụ trách</div>
                <div class="kpi-value"><?= $totalAssignedTopics ?? 0 ?></div>
                <div class="kpi-sub"><span class="kpi-badge neu"><i class="fa-solid fa-bookmark"></i> Đã phân công</span></div>
            </div>
        </div>

        <div class="kpi-card teal">
            <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Khối lượng sinh viên</div>
                <div class="kpi-value"><?= $wl ?> <span style="font-size:16px;font-weight:500;color:#64748b">/ <?= $qt ?></span></div>
                <div style="margin-top:8px">
                    <div class="workload-bar">
                        <div class="workload-bar-fill <?= $wlCls ?>" style="width:<?= min($pct,100) ?>%"></div>
                    </div>
                    <div class="kpi-sub"><?= $pct ?>% công suất</div>
                </div>
            </div>
        </div>

        <div class="kpi-card amber">
            <div class="kpi-icon"><i class="fa-solid fa-file-signature"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Chờ chấm điểm</div>
                <div class="kpi-value"><?= $totalPendingReviews ?? 0 ?></div>
                <div class="kpi-sub">
                    <?php if(($totalPendingReviews ?? 0) > 0): ?>
                        <span class="kpi-badge down"><i class="fa-solid fa-clock"></i> Cần xử lý</span>
                    <?php else: ?>
                        <span class="kpi-badge up"><i class="fa-solid fa-check"></i> Đã cập nhật</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ SUBMISSIONS TABLE ══ -->
    <div class="dash-box">
        <div class="dash-box-header">
            <div class="dash-box-title">
                <span class="title-dot" style="background:#f59e0b"></span>
                <i class="fa-solid fa-file-signature" style="color:#f59e0b;font-size:14px"></i>
                Bài nộp cần chấm điểm
            </div>
            <a href="index.php?page=scores" class="btn btn-light btn-sm">Xem tất cả</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Đề tài</th>
                        <th>Sinh viên</th>
                        <th>Nộp lúc</th>
                        <th style="width:100px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($pendingReviews)): ?>
                        <?php foreach(array_slice($pendingReviews, 0, 6) as $review): ?>
                            <tr>
                                <td class="fw-semibold" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    <?= htmlspecialchars($review['topic_title']) ?>
                                </td>
                                <td><?= htmlspecialchars($review['student_name']) ?></td>
                                <td class="text-muted" style="font-size:13px">
                                    <?= date('d/m/Y H:i', strtotime($review['submitted_at'])) ?>
                                </td>
                                <td>
                                    <a href="index.php?page=submission-show&id=<?= $review['id'] ?>"
                                       class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-star-half-stroke"></i> Chấm
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">
                            <i class="fa-regular fa-circle-check fa-lg mb-2 d-block text-success"></i>
                            Không có bài nộp nào cần chấm
                        </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require '../app/views/layouts/footer.php'; ?>
