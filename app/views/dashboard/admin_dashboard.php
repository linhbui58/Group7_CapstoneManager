<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <div class="page-header">
        <div>
            <h2>Dashboard Overview</h2>
            <p class="text-muted small mb-0 mt-1">Professional Capstone Management System</p>
        </div>
        <div class="quick-actions d-flex gap-2">
            <?php if($_SESSION['user']['role'] == 'admin'): ?>
                <a href="index.php?page=topic-create" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> New Topic
                </a>
                <a href="index.php?page=students" class="btn btn-success">
                    <i class="fa-solid fa-user-graduate"></i> Students
                </a>
                <a href="index.php?page=lecturers" class="btn btn-warning">
                    <i class="fa-solid fa-chalkboard-user"></i> Lecturers
                </a>
            <?php endif; ?>

            <?php if($_SESSION['user']['role'] == 'student'): ?>
                <a href="index.php?page=topic-create" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Submit Topic
                </a>
                <a href="index.php?page=submissions" class="btn btn-success">
                    <i class="fa-solid fa-file-lines"></i> My Submissions
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="stats-grid-dashboard mb-4" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--info-light); color: var(--info);">
                <i class="fa-solid fa-book"></i>
            </div>
            <div class="stat-info">
                <h5 class="text-muted small fw-bold mb-1" style="text-transform: uppercase; letter-spacing: 0.5px;">Total Topics</h5>
                <h2 id="topic-count" class="fw-bold mb-0" style="font-size: 28px;"><?= $totalTopics ?? 0 ?></h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--success-light); color: var(--success);">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-info">
                <h5 class="text-muted small fw-bold mb-1" style="text-transform: uppercase; letter-spacing: 0.5px;">Students</h5>
                <h2 class="fw-bold mb-0" style="font-size: 28px;"><?= $totalStudents ?? 0 ?></h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--warning-light); color: var(--warning);">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="stat-info">
                <h5 class="text-muted small fw-bold mb-1" style="text-transform: uppercase; letter-spacing: 0.5px;">Lecturers</h5>
                <h2 class="fw-bold mb-0" style="font-size: 28px;"><?= $totalLecturers ?? 0 ?></h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: var(--danger-light); color: var(--danger);">
                <i class="fa-solid fa-file-import"></i>
            </div>
            <div class="stat-info">
                <h5 class="text-muted small fw-bold mb-1" style="text-transform: uppercase; letter-spacing: 0.5px;">Submissions</h5>
                <h2 class="fw-bold mb-0" style="font-size: 28px;"><?= $totalSubmissions ?? 0 ?></h2>
            </div>
        </div>
    </div>

    <div class="row mb-4 align-items-stretch">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="dashboard-box d-flex flex-column h-100">
                <div class="box-header mb-4 d-flex align-items-center justify-content-between">
                    <h4 class="fw-bold m-0" style="font-size: 18px;"><i class="fa-solid fa-list-check me-2 text-primary"></i>Recent Topics</h4>
                    <a href="index.php?page=topics" class="btn btn-light btn-sm px-3 fw-bold">View All</a>
                </div>
                <div class="table-responsive flex-grow-1 d-flex flex-column">
                    <table class="table datatable flex-grow-1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Topic Title</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($recentTopics)): ?>
                            <?php foreach($recentTopics as $topic): ?>
                                <?php $status = $topic['status'] ?? 'pending'; ?>
                                <tr>
                                    <td class="fw-bold text-muted">#<?= $topic['id']; ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($topic['title']); ?></td>
                                    <td>
                                        <span class="badge badge-<?= htmlspecialchars($status); ?>">
                                            <?= ucfirst(htmlspecialchars($status)); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted py-4">No topics found</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-box d-flex flex-column h-100">
                <div class="box-header mb-4 d-flex align-items-center justify-content-between">
                    <h4 class="fw-bold m-0" style="font-size: 18px;"><i class="fa-solid fa-paper-plane me-2 text-success"></i>Recent Submissions</h4>
                    <a href="index.php?page=submissions" class="btn btn-light btn-sm px-3 fw-bold">View All</a>
                </div>
                <div class="table-responsive flex-grow-1 d-flex flex-column">
                    <table class="table datatable flex-grow-1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($recentSubmissions)): ?>
                            <?php foreach($recentSubmissions as $sub): ?>
                                <?php $status = $sub['status'] ?? 'pending'; ?>
                                <tr>
                                    <td class="fw-bold text-muted">#<?= $sub['id']; ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($sub['student_name']); ?></td>
                                    <td>
                                        <span class="badge badge-<?= htmlspecialchars($status); ?>">
                                            <?= ucfirst(htmlspecialchars($status)); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted py-4">No submissions found</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-box">
        <div class="box-header mb-4">
            <h4 class="fw-bold m-0" style="font-size: 18px;"><i class="fa-solid fa-chart-column me-2 text-info"></i>Topic Statistics Overview</h4>
        </div>
        <div style="height: 350px; position: relative;">
            <canvas id="topicChart"></canvas>
        </div>
    </div>

</div>

<script>
$(document).ready(function(){
    // Tắt thông báo Alert mặc định của DataTables
    $.fn.dataTable.ext.errMode = 'none';

    $('.datatable').DataTable({
        destroy: true,
        retrieve: true,
        pageLength: 5,
        responsive: true,
        dom: 'rt<"d-flex justify-content-between align-items-center p-3 mt-auto w-100"ip>', 
        language: { search: "" , searchPlaceholder: "Quick search...", paginate: { previous: "Prev", next: "Next" }},
        drawCallback: function(settings) {
            var api = this.api();
            var rows = api.rows({page:'current'}).nodes();
            var pageLength = api.page.len();
            var remaining = pageLength - rows.length;
            
            // Nếu không có dòng nào (ví dụ: mảng rỗng), DataTables sẽ hiển thị 1 dòng "No data".
            // Ta cần kiểm tra nếu có dòng "dataTables_empty" thì remaining = pageLength - 1.
            if (rows.length === 1 && $(rows[0]).find('.dataTables_empty').length > 0) {
                remaining = pageLength - 1;
            }

            if(remaining > 0) {
                var emptyRow = '<tr><td>&nbsp;</td><td></td><td></td></tr>';
                for(var i = 0; i < remaining; i++) {
                    $(this).children('tbody').append(emptyRow);
                }
            }
        }
    });
});

const ctx = document.getElementById('topicChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Draft', 'Pending', 'Approved', 'Rejected'],
            datasets: [{
                label: 'Topics Distribution',
                data: [
                    <?= $draftCount ?? 0 ?>,
                    <?= $pendingCount ?? 0 ?>,
                    <?= $approvedCount ?? 0 ?>,
                    <?= $rejectedCount ?? 0 ?>
                ],
                backgroundColor: ['#94a3b8', '#f59e0b', '#10b981', '#ef4444'],
                borderRadius: 12,
                barThickness: 50
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                y: { beginAtZero: true, grid: { color: '#e2e8f0' }, ticks: { stepSize: 1, color: '#475569', font: { weight: '600', family: "'Inter', sans-serif" } } },
                x: { grid: { display: false }, ticks: { color: '#475569', font: { weight: '600', family: "'Inter', sans-serif" } } }
            }
        }
    });
}
</script>

<?php require '../app/views/layouts/footer.php'; ?>