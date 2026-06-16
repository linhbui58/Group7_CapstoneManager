<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe; min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0f172a;">System Logs</h2>
            <p class="text-muted mb-0">Lịch sử hoạt động chi tiết của người dùng trên hệ thống.</p>
        </div>
        <button onclick="window.location.reload()" class="btn btn-white shadow-sm rounded-pill px-4 fw-bold">
            <i class="fa-solid fa-arrows-rotate me-1"></i> Làm mới
        </button>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table datatable table-hover mb-0" id="systemLogTable">
                    <thead>
                        <tr class="text-muted small">
                            <th class="ps-4">THỜI GIAN</th>
                            <th>NGƯỜI DÙNG</th>
                            <th>HÀNH ĐỘNG</th>
                            <th>CHI TIẾT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($logs)): ?>
                            <?php foreach($logs as $log): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold" style="font-size: 0.85rem;">
                                            <?= date('H:i:s', strtotime($log['created_at'])) ?>
                                        </div>
                                        <div class="text-muted small"><?= date('d/m/Y', strtotime($log['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($log['email'] ?? 'Hệ thống') ?></span>
                                        <div class="text-muted small" style="font-size: 0.7rem;"><?= strtoupper($log['role'] ?? 'N/A') ?></div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 bg-light text-dark border">
                                            <?= htmlspecialchars($log['action']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <code class="text-primary small"><?= htmlspecialchars($log['details'] ?? '---') ?></code>
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
    $('#systemLogTable').DataTable({
        "order": [[ 0, "desc" ]], // Mới nhất lên đầu
        "language": {
            "search": "",
            "searchPlaceholder": "Tìm kiếm nhật ký...",
            "lengthMenu": "_MENU_ dòng/trang",
        },
        "dom": '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small"i><"small"p>>',
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>