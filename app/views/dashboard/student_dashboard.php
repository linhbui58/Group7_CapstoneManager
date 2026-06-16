<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h2>Student Dashboard</h2>
            <p class="text-muted small mb-0 mt-1">Welcome back, <?= htmlspecialchars($student['full_name'] ?? 'Student') ?></p>
        </div>
        <div class="quick-actions d-flex gap-2">
            <a href="index.php?page=submission-create" class="btn btn-primary">
                <i class="fa-solid fa-paper-plane"></i> Submit Work
            </a>
        </div>
    </div>

    <div class="stats-grid-dashboard mb-4" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--info-light); color: var(--info);">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <div class="stat-info">
                <h5 class="text-muted small fw-bold mb-1" style="text-transform: uppercase; letter-spacing: 0.5px;">Registered Topic</h5>
                <h2 class="fw-bold mb-0 text-truncate" style="font-size: 20px; max-width: 200px;" title="<?= htmlspecialchars($registeredTopic['title'] ?? 'None') ?>">
                    <?= $registeredTopic ? htmlspecialchars($registeredTopic['title']) : 'None' ?>
                </h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--success-light); color: var(--success);">
                <i class="fa-solid fa-bars-progress"></i>
            </div>
            <div class="stat-info">
                <h5 class="text-muted small fw-bold mb-1" style="text-transform: uppercase; letter-spacing: 0.5px;">Progress</h5>
                <div class="d-flex align-items-center gap-2">
                    <div class="progress flex-grow-1" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progress['progress_percentage'] ?>%;" aria-valuenow="<?= $progress['progress_percentage'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="fw-bold"><?= $progress['progress_percentage'] ?>%</span>
                </div>
                <small class="text-muted"><?= $progress['total_submissions'] ?> / <?= $progress['total_milestones'] ?> Milestones</small>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--warning-light); color: var(--warning);">
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="stat-info">
                <h5 class="text-muted small fw-bold mb-1" style="text-transform: uppercase; letter-spacing: 0.5px;">Average Score</h5>
                <h2 class="fw-bold mb-0" style="font-size: 28px;"><?= number_format($progress['average_score'], 1) ?></h2>
            </div>
        </div>
    </div>

    <div class="dashboard-box">
        <div class="box-header mb-4 d-flex align-items-center justify-content-between">
            <h4 class="fw-bold m-0" style="font-size: 18px;"><i class="fa-solid fa-clock me-2 text-danger"></i>Upcoming Milestones</h4>
            <a href="index.php?page=milestones" class="btn btn-light btn-sm px-3 fw-bold">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($upcomingMilestones)): ?>
                        <?php foreach($upcomingMilestones as $ms): ?>
                            <tr>
                                <td class="fw-semibold text-uppercase"><?= htmlspecialchars($ms['title']) ?></td>
                                <td><?= date('Y-m-d H:i', strtotime($ms['deadline'])) ?></td>
                                <td>
                                    <?php
                                        $diff = strtotime($ms['deadline']) - time();
                                        $days = floor($diff / (60 * 60 * 24));
                                    ?>
                                    <span class="badge badge-<?= $days < 3 ? 'danger' : 'warning' ?>">
                                        In <?= $days ?> days
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center text-muted">No upcoming milestones.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
