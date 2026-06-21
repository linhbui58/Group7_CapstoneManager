<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<?php
    $hour  = (int)date('H');
    $greet = $hour < 12 ? 'Chào buổi sáng' : ($hour < 18 ? 'Chào buổi chiều' : 'Chào buổi tối');
    $uname = htmlspecialchars($lecturer['full_name'] ?? explode('@', ($_SESSION['user']['email'] ?? ''))[0]);
    $wl    = $workload ?? 0;
    $qt    = $quota    ?? 8;
    $pct   = $qt > 0 ? round($wl / $qt * 100) : 0;
    $wlCls = $pct >= 90 ? 'workload-full' : ($pct >= 60 ? 'workload-warn' : 'workload-ok');
    $svCount = count($supervisedStudents ?? []);
?>

<div class="main-content">

    <!-- ══ HERO ══ -->
    <div class="dash-hero">
        <div class="dash-hero-left">
            <div class="dash-greeting"><?= $greet ?> 👋</div>
            <div class="dash-username"><?= $uname ?></div>
            <div class="dash-meta" style="margin-top:8px">
                <i class="fa-solid fa-graduation-cap me-1"></i>
                Khoa/Viện: <?= htmlspecialchars($lecturer['faculty'] ?? 'Chưa cập nhật') ?>
                &nbsp;·&nbsp;
                <i class="fa-solid fa-phone me-1"></i>
                <?= htmlspecialchars($lecturer['phone'] ?? 'Chưa cập nhật') ?>
            </div>
            <div class="dash-meta" style="margin-top:8px">
                <?= date('l, d/m/Y') ?> &nbsp;·&nbsp; <span>Giảng viên</span>
            </div>
        </div>
        <div class="dash-hero-right">
            <a href="index.php?page=lecturer-edit&id=<?= $lecturer['id'] ?>" class="btn-hero btn-hero-primary">
                <i class="fa-solid fa-pen"></i> Cập nhật profile
            </a>
            <a href="index.php?page=scores" class="btn-hero btn-hero-accent">
                <i class="fa-solid fa-star"></i> Chấm điểm
            </a>
        </div>
    </div>

    <!-- ══ KPI GRID ══ -->
    <div class="kpi-grid" style="grid-template-columns:repeat(4,1fr)">

        <div class="kpi-card blue">
            <div class="kpi-icon"><i class="fa-solid fa-book"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Đề tài phụ trách</div>
                <div class="kpi-value"><?= $totalAssignedTopics ?? 0 ?></div>
                <div class="kpi-sub"><span class="kpi-badge neu"><i class="fa-solid fa-bookmark"></i> Đã phân công</span></div>
            </div>
        </div>

        <div class="kpi-card green">
            <div class="kpi-icon"><i class="fa-solid fa-user-graduate"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Sinh viên hướng dẫn</div>
                <div class="kpi-value"><?= $svCount ?></div>
                <div class="kpi-sub"><span class="kpi-badge neu"><i class="fa-solid fa-users"></i> Đang hướng dẫn</span></div>
            </div>
        </div>

        <div class="kpi-card teal">
            <div class="kpi-icon"><i class="fa-solid fa-gauge"></i></div>
            <div class="kpi-body">
                <div class="kpi-label">Khối lượng</div>
                <div class="kpi-value"><?= $wl ?><span style="font-size:16px;font-weight:500;color:#64748b"> / <?= $qt ?></span></div>
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
                    <?php if (($totalPendingReviews ?? 0) > 0): ?>
                        <span class="kpi-badge down"><i class="fa-solid fa-clock"></i> Cần xử lý</span>
                    <?php else: ?>
                        <span class="kpi-badge up"><i class="fa-solid fa-check"></i> Đã cập nhật</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ 2 COLUMN ROW ══ -->
    <div class="row g-4">

        <!-- Bài nộp cần chấm -->
        <div class="col-lg-6">
            <div class="dash-box h-100">
                <div class="dash-box-header">
                    <div class="dash-box-title">
                        <span class="title-dot" style="background:#f59e0b"></span>
                        <i class="fa-solid fa-file-signature" style="color:#f59e0b;font-size:14px"></i>
                        Bài nộp cần chấm
                    </div>
                    <a href="index.php?page=submissions" class="btn btn-light btn-sm rounded-pill px-3" style="font-size:12px">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Đề tài</th><th>Sinh viên</th><th>Nộp lúc</th><th></th></tr></thead>
                        <tbody>
                        <?php if (!empty($pendingReviews)): ?>
                            <?php foreach (array_slice($pendingReviews, 0, 5) as $r): ?>
                            <tr>
                                <td class="fw-semibold" style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:13px">
                                    <?= htmlspecialchars($r['topic_title']) ?>
                                </td>
                                <td style="font-size:13px"><?= htmlspecialchars($r['student_name']) ?></td>
                                <td class="text-muted" style="font-size:11px"><?= date('d/m H:i', strtotime($r['submitted_at'])) ?></td>
                                <td>
                                    <a href="index.php?page=submission-show&id=<?= $r['id'] ?>"
                                       class="btn btn-sm btn-primary rounded-pill px-3" style="font-size:11px">
                                        <i class="fa-solid fa-star" style="font-size:10px"></i> Chấm
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted py-4" style="font-size:13px">
                                <i class="fa-regular fa-circle-check d-block mb-1 fa-lg text-success"></i>
                                Không có bài nộp cần chấm
                            </td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sinh viên hướng dẫn -->
        <div class="col-lg-6">
            <div class="dash-box h-100">
                <div class="dash-box-header">
                    <div class="dash-box-title">
                        <span class="title-dot" style="background:#6366f1"></span>
                        <i class="fa-solid fa-user-graduate" style="color:#6366f1;font-size:14px"></i>
                        Sinh viên tôi hướng dẫn
                    </div>
                    <span style="background:#eff6ff;color:#4f46e5;font-size:12px;padding:4px 12px;border-radius:20px;font-weight:700">
                        <?= $svCount ?> SV
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Sinh viên</th><th>Đề tài</th><th>Học kỳ</th></tr></thead>
                        <tbody>
                        <?php if (!empty($supervisedStudents)): ?>
                            <?php foreach ($supervisedStudents as $sv): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:30px;height:30px;border-radius:9px;background:#eff6ff;color:#4f46e5;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0">
                                            <i class="fa-solid fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:13px"><?= htmlspecialchars($sv['full_name']) ?></div>
                                            <div class="text-muted" style="font-size:11px"><?= htmlspecialchars($sv['faculty'] ?? '—') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:12px;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#475569"
                                    title="<?= htmlspecialchars($sv['topic_title'] ?? '') ?>">
                                    <?= htmlspecialchars($sv['topic_title'] ?? 'Chưa đăng ký') ?>
                                </td>
                                <td>
                                    <span style="background:#f1f5f9;color:#334155;font-size:11px;padding:3px 8px;border-radius:6px;font-weight:600">
                                        <?= htmlspecialchars($sv['semester_name']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted py-4" style="font-size:13px">
                                <i class="fa-solid fa-user-slash d-block mb-1 fa-lg" style="opacity:.3"></i>
                                Chưa có sinh viên nào
                            </td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require '../app/views/layouts/footer.php'; ?>
