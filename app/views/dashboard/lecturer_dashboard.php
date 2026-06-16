<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <div>
            <h2>Lecturer Dashboard</h2>
            <p class="text-muted small mb-0 mt-1">Welcome back, <?= htmlspecialchars($lecturer['full_name'] ?? 'Lecturer') ?></p>
        </div>
    </div>

    <div class="stats-grid-dashboard mb-4" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--info-light); color: var(--info);">
                <i class="fa-solid fa-book"></i>
            </div>
            <div class="stat-info">
                <h5 class="text-muted small fw-bold mb-1" style="text-transform: uppercase; letter-spacing: 0.5px;">Assigned Topics</h5>
                <h2 class="fw-bold mb-0" style="font-size: 28px;"><?= $totalAssignedTopics ?></h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--success-light); color: var(--success);">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-info">
                <h5 class="text-muted small fw-bold mb-1" style="text-transform: uppercase; letter-spacing: 0.5px;">Workload (Students)</h5>
                <h2 class="fw-bold mb-0" style="font-size: 28px;"><?= $workload ?> / <?= $quota ?></h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--warning-light); color: var(--warning);">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div class="stat-info">
                <h5 class="text-muted small fw-bold mb-1" style="text-transform: uppercase; letter-spacing: 0.5px;">Pending Reviews</h5>
                <h2 class="fw-bold mb-0" style="font-size: 28px;"><?= $totalPendingReviews ?></h2>
            </div>
        </div>
    </div>

    <div class="dashboard-box">
        <div class="box-header mb-4 d-flex align-items-center justify-content-between">
            <h4 class="fw-bold m-0" style="font-size: 18px;"><i class="fa-solid fa-file-signature me-2 text-warning"></i>Submissions Needing Review</h4>
            <a href="index.php?page=scores" class="btn btn-light btn-sm px-3 fw-bold">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Topic</th>
                        <th>Student</th>
                        <th>Submitted At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($pendingReviews)): ?>
                        <?php foreach(array_slice($pendingReviews, 0, 5) as $review): ?>
                            <tr>
                                <td><?= htmlspecialchars($review['topic_title']) ?></td>
                                <td><?= htmlspecialchars($review['student_name']) ?></td>
                                <td><?= htmlspecialchars($review['submitted_at']) ?></td>
                                <td>
                                    <a href="index.php?page=submission-show&id=<?= $review['id'] ?>" class="btn btn-sm btn-primary">Review</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted">No pending reviews.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
