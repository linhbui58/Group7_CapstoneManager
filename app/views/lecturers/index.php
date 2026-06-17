<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Inter', sans-serif; }
    .main-content { background-color: #f4f7fe !important; }
    
    /* Card & Table Styling */
    .custom-card {
        border-radius: 1.25rem;
        border: none;
        box-shadow: 0 10px 30px rgba(160, 174, 192, 0.1);
        background: #fff;
        overflow: hidden;
    }

    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table tbody td {
        padding: 1.1rem 1rem;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    /* Avatar giả lập */
    .avatar-circle {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        margin-right: 15px;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }

    /* Nút bấm Action */
    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        text-decoration: none;
    }
    
    .btn-action:hover { 
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .badge-expertise {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        font-weight: 600;
        padding: 0.5rem 1rem !important;
    }

    /* Tùy chỉnh thanh Search của DataTable */
    .dataTables_filter input {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        outline: none;
        transition: all 0.2s;
    }
    .dataTables_filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
</style>

<div class="main-content" style="padding: 40px;">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.02em;">Quản lý giảng viên</h2>
            <p class="text-muted mb-0">Danh sách giảng viên trong hệ thống.</p>
        </div>
        <?php if($_SESSION['user']['role'] == 'admin'): ?>
            <a href="index.php?page=lecturer-create" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-bold d-flex align-items-center" style="background: #6366f1; border: none;">
                <i class="fa-solid fa-plus me-2"></i> Thêm giảng viên
            </a>
        <?php endif; ?>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="custom-card p-3 d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary" style="color: #6366f1 !important;">
                    <i class="fa-solid fa-user-tie fa-lg"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-medium">Tổng số</small>
                    <span class="fw-bold h5 mb-0"><?= count($lecturers ?? []) ?> giảng viên</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table datatable table-hover mb-0" id="lecturerTable">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Thông tin giảng viên</th>
                            <th>Chuyên môn</th>
                            <?php if($_SESSION['user']['role'] == 'admin'): ?>
                                <th class="text-end pe-4">Thao tác</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($lecturers)): ?>
                            <?php foreach($lecturers as $lecturer): ?>
                                <tr>
                                    <td class="ps-4 fw-medium text-muted">
                                        #<?= $lecturer['id'] ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle">
                                                <?= strtoupper(substr($lecturer['full_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="color: #1e293b;"><?= htmlspecialchars($lecturer['full_name']) ?></div>
                                                <div class="small text-muted font-monospace"><?= htmlspecialchars($lecturer['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-expertise rounded-pill small">
                                            <i class="fa-solid fa-brain me-1 small"></i>
                                            <?= htmlspecialchars($lecturer['expertise']) ?>
                                        </span>
                                    </td>
                                    <?php if($_SESSION['user']['role'] == 'admin'): ?>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="index.php?page=lecturer-show&id=<?= $lecturer['id'] ?>" 
                                                   class="btn-action bg-info bg-opacity-10 text-info" title="Xem chi tiết">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="index.php?page=lecturer-edit&id=<?= $lecturer['id'] ?>" 
                                                   class="btn-action bg-warning bg-opacity-10 text-warning" title="Chỉnh sửa">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="index.php?page=lecturer-delete&id=<?= $lecturer['id'] ?>" 
                                                   class="btn-action bg-danger bg-opacity-10 text-danger" 
                                                   onclick="return confirm('Bạn chắc chắn muốn xóa giảng viên này?')" title="Xóa">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <p class="mb-0">Chưa có giảng viên nào trong hệ thống.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Tắt thông báo warning của DataTables
    $.fn.dataTable.ext.errMode = 'none';

    // Hàm khởi tạo an toàn
    function initLecturerTable() {
        if ($.fn.DataTable.isDataTable('#lecturerTable')) {
            $('#lecturerTable').DataTable().destroy();
        }
        
        $('#lecturerTable').DataTable({
            "retrieve": true,
            "responsive": true,
            "language": {
                "search": "",
                "searchPlaceholder": "Tìm kiếm giảng viên...",
                "lengthMenu": "_MENU_ dòng/trang",
                "info": "Hiển thị _START_ đến _END_ trong _TOTAL_ giảng viên",
                "paginate": {
                    "next": '<i class="fa-solid fa-chevron-right"></i>',
                    "previous": '<i class="fa-solid fa-chevron-left"></i>'
                }
            },
            "dom": '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small"i><"small"p>>',
        });
    }

    initLecturerTable();
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>