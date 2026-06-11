<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Danh Sách Phân Công</h2>
        <a href="index.php?page=assignment-create" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-plus me-1"></i> Thêm phân công
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-3">
            <table class="table datatable table-hover" id="assignmentsTable">
                <thead>
                    <tr class="text-muted small">
                        <th>ĐỀ TÀI</th>
                        <th>GIẢNG VIÊN HƯỚNG DẪN</th>
                        <th>NGÀY PHÂN CÔNG</th>
                        <th class="text-end">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($assignments as $a): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($a['topic_title']) ?></td>
                            <td><?= htmlspecialchars($a['lecturer_name']) ?></td>
                            <td><?= date('d/m/Y', strtotime($a['assigned_at'])) ?></td>
                            <td class="text-end">
                                <a href="index.php?page=assignment-delete&id=<?= $a['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                   onclick="return confirm('Xóa phân công này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>