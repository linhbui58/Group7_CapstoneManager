<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
    .milestone-container { padding: 40px; background-color: #f8fafc; min-height: 100vh; }
    .card-custom { border-radius: 20px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.03); background: #fff; }
    .title-label { color: #0f172a; font-weight: 700; text-transform: capitalize; }
    .badge-status { padding: 8px 16px; border-radius: 50px; font-size: 11px; font-weight: 700; }
</style>

<div class="milestone-container main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0" style="color: #1e293b; letter-spacing: -1px;">Quản Lý Lộ Trình</h2>
            <p class="text-muted small mb-0">Thiết lập các giai đoạn nộp bài và thời hạn khóa hệ thống.</p>
        </div>
        <a href="index.php?page=milestone-create" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="fa-solid fa-calendar-plus me-2"></i> Thiết lập Cột mốc
        </a>
    </div>

    <div class="card card-custom">
        <div class="card-body p-4">
            <table class="table datatable table-hover align-middle" id="milestonesTable">
                <thead>
                    <tr class="text-muted small">
                        <th class="ps-3">GIAI ĐOẠN</th>
                        <th>HỌC KỲ</th>
                        <th>HẠN CHÓT (DEADLINE)</th>
                        <th>TRẠNG THÁI</th>
                        <th class="text-end pe-3">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($milestones)): foreach($milestones as $m): 
                        $isOverdue = strtotime(date('Y-m-d H:i:s')) > strtotime($m['deadline']);
                        $titleVn = ['proposal' => 'Đề cương', 'midterm' => 'Giữa kỳ', 'final' => 'Cuối kỳ'];
                    ?>
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    <div class="p-2 bg-primary bg-opacity-10 rounded-3 me-3 text-primary">
                                        <i class="fa-solid fa-flag-checkered"></i>
                                    </div>
                                    <span class="title-label"><?= $titleVn[$m['title']] ?? $m['title'] ?></span>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-secondary border"><?= htmlspecialchars($m['semester_name'] ?? 'N/A') ?></span></td>
                            <td>
                                <span class="<?= $isOverdue ? 'text-danger fw-bold' : 'text-success' ?>">
                                    <?= date('H:i | d/m/Y', strtotime($m['deadline'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if($isOverdue): ?>
                                    <span class="badge-status bg-danger bg-opacity-10 text-danger border">HẾT HẠN</span>
                                <?php else: ?>
                                    <span class="badge-status bg-success bg-opacity-10 text-success border">ĐANG MỞ</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3">
                                <a href="index.php?page=milestone-edit&id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3">Sửa</a>
                                <a href="index.php?page=milestone-delete&id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Xóa?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox d-block mb-2 fs-3"></i>
                                Chưa có cột mốc nào được thiết lập.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>