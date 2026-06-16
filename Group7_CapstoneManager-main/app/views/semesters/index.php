<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
    .custom-card { border-radius: 1.25rem; border: none; box-shadow: 0 10px 30px rgba(160, 174, 192, 0.1); background: #fff; }
    .badge-date { background: rgba(99, 102, 241, 0.1); color: #6366f1; font-weight: 600; }
    .btn-action { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: all 0.3s; border: none; }
    .btn-action:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe;">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0f172a;">Semesters Management</h2>
            <p class="text-muted mb-0">Manage academic periods and deadlines.</p>
        </div>
        <?php if($_SESSION['user']['role'] == 'admin'): ?>
            <a href="index.php?page=semester-create" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-bold" style="background: #6366f1; border: none;">
                <i class="fa-solid fa-plus me-2"></i> Add Semester
            </a>
        <?php endif; ?>
    </div>

    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table datatable table-hover mb-0" id="semesterTable">
                    <thead>
                        <tr class="text-muted small">
                            <th class="ps-4">ID</th>
                            <th>Semester Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <?php if($_SESSION['user']['role'] == 'admin'): ?>
                                <th class="text-end pe-4">Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($semesters)): ?>
                            <?php foreach($semesters as $s): ?>
                                <tr>
                                    <td class="ps-4 text-muted fw-bold">#<?= $s['id'] ?></td>
                                    <td class="fw-bold" style="color: #1e293b;"><?= htmlspecialchars($s['name']) ?></td>
                                    <td><span class="badge badge-date px-3 py-2 rounded-pill"><?= date('d/m/Y', strtotime($s['start_date'])) ?></span></td>
                                    <td><span class="badge badge-date px-3 py-2 rounded-pill"><?= date('d/m/Y', strtotime($s['end_date'])) ?></span></td>
                                    <?php if($_SESSION['user']['role'] == 'admin'): ?>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="index.php?page=semester-edit&id=<?= $s['id'] ?>" class="btn-action bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="index.php?page=semester-delete&id=<?= $s['id'] ?>" class="btn-action bg-danger bg-opacity-10 text-danger" onclick="return confirm('Delete this semester?')"><i class="fa-solid fa-trash-can"></i></a>
                                            </div>
                                        </td>
                                    <?php endif; ?>
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
    if ($.fn.DataTable.isDataTable('#semesterTable')) { $('#semesterTable').DataTable().destroy(); }
    $('#semesterTable').DataTable({
        "retrieve": true,
        "language": { "search": "", "searchPlaceholder": "Search semester..." },
        "dom": '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small"i><"small"p>>',
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>