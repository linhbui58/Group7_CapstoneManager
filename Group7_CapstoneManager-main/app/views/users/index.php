<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <div class="page-header">
        <div>
            <h2>Users Management</h2>
            <p class="text-muted small mb-0 mt-1">Quản lý tài khoản người dùng trong hệ thống</p>
        </div>
        <a href="index.php?page=user-create" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Thêm người dùng
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table datatable" id="userTable">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#<?= $u['id'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($u['email']) ?></td>
                                <td>
                                    <span class="badge role-<?= $u['role'] ?>">
                                        <?= ucfirst($u['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge status-<?= $u['status'] ?? 'active' ?>">
                                        <?= ucfirst($u['status'] ?? 'active') ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="index.php?page=user-show&id=<?= $u['id'] ?>"
                                           class="btn-action bg-info bg-opacity-10 text-info" title="Xem">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <?php if ($u['role'] !== 'admin'): ?>
                                            <a href="index.php?page=user-edit&id=<?= $u['id'] ?>"
                                               class="btn-action bg-warning bg-opacity-10 text-warning" title="Sửa">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <?php if (($u['status'] ?? 'active') === 'active'): ?>
                                                <a href="index.php?page=user-lock&id=<?= $u['id'] ?>"
                                                   class="btn-action bg-danger bg-opacity-10 text-danger" title="Khóa">
                                                    <i class="fa-solid fa-lock"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="index.php?page=user-unlock&id=<?= $u['id'] ?>"
                                                   class="btn-action bg-success bg-opacity-10 text-success" title="Mở khóa">
                                                    <i class="fa-solid fa-lock-open"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="index.php?page=user-delete&id=<?= $u['id'] ?>"
                                               class="btn-action btn-delete bg-danger bg-opacity-10 text-danger" title="Xóa">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users fa-2x mb-3 d-block" style="opacity:.3"></i>
                                Chưa có người dùng nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $.fn.dataTable.ext.errMode = 'none';
    if ($.fn.DataTable.isDataTable('#userTable')) $('#userTable').DataTable().destroy();
    $('#userTable').DataTable({
        language: { search: '', searchPlaceholder: 'Tìm kiếm người dùng...' },
        dom: '<"d-flex justify-content-between align-items-center p-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center p-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
