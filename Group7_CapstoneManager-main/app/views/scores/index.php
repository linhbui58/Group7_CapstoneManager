<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
    .custom-card { border-radius: 1.25rem; border: none; box-shadow: 0 10px 30px rgba(160, 174, 192, 0.1); background: #fff; }
    .score-circle { 
        width: 45px; height: 45px; border-radius: 12px; 
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.1rem;
    }
    .score-high { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .score-mid { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .score-low { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
</style>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe; min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0f172a;">Evaluation Results</h2>
            <p class="text-muted mb-0">Theo dõi và quản lý điểm số bảo vệ đồ án của sinh viên.</p>
        </div>
        <?php if($_SESSION['user']['role'] == 'lecturer'): ?>
            <a href="index.php?page=score-create" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-star me-2"></i> Chấm điểm mới
            </a>
        <?php endif; ?>
    </div>

    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table datatable table-hover mb-0" id="scoreTable">
                    <thead>
                        <tr class="text-muted small">
                            <th class="ps-4">STUDENT & TOPIC</th>
                            <th class="text-center">SCORE</th>
                            <th>SUPERVISOR</th>
                            <th>FEEDBACK</th>
                            <th class="text-end pe-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($scores)): ?>
                            <?php foreach($scores as $s): ?>
                                <?php 
                                    $scoreClass = ($s['score'] >= 8) ? 'score-high' : (($s['score'] >= 5) ? 'score-mid' : 'score-low');
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($s['student_name']) ?></div>
                                        <div class="small text-muted text-truncate" style="max-width: 250px;"><?= htmlspecialchars($s['topic_title']) ?></div>
                                    </td>
                                    <td class="text-center">
                                        <div class="score-circle <?= $scoreClass ?> mx-auto">
                                            <?= number_format($s['score'], 1) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small"><?= htmlspecialchars($s['lecturer_name']) ?></div>
                                        <div class="text-muted" style="font-size: 0.75rem;"><?= date('d/m/Y', strtotime($s['graded_at'])) ?></div>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block text-truncate" style="max-width: 200px;">
                                            <?= htmlspecialchars($s['feedback'] ?? 'No feedback provided.') ?>
                                        </small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="index.php?page=score-edit&id=<?= $s['id'] ?>" class="text-warning p-2"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="index.php?page=score-delete&id=<?= $s['id'] ?>" class="text-danger p-2" onclick="return confirm('Xóa điểm này?')"><i class="fa-solid fa-trash-can"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $('#scoreTable').DataTable({
        "retrieve": true,
        "language": { "search": "", "searchPlaceholder": "Tìm kiếm kết quả..." },
        "dom": '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small"i><"small"p>>',
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>