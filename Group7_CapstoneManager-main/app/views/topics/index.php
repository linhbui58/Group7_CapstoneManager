<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<?php $role = $_SESSION['user']['role']; ?>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe; min-height: 100vh;">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Quản Lý Đề Tài</h2>
            <p class="text-muted small mb-0">
                <?php if ($role === 'admin'): ?>
                    Toàn bộ đề tài trong hệ thống
                <?php elseif ($role === 'lecturer'): ?>
                    Đề tài sinh viên đăng ký chọn bạn
                <?php else: ?>
                    Danh sách đề tài có thể đăng ký
                <?php endif; ?>
            </p>
        </div>
        <?php if (in_array($role, ['admin', 'student'])): ?>
            <a href="index.php?page=topic-create" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-plus me-1"></i>
                <?= $role === 'admin' ? 'Thêm đề tài' : 'Đề xuất đề tài mới' ?>
            </a>
        <?php endif; ?>
    </div>

    <!-- Flash messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Filter bar -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-3">
            <form method="GET" action="index.php" class="row g-2 align-items-end">
                <input type="hidden" name="page" value="topics">

                <!-- Tìm kiếm -->
                <div class="col-md-<?= $role === 'student' ? '7' : '5' ?>">
                    <label class="form-label fw-bold small text-muted mb-1">TÌM KIẾM</label>
                    <input type="text" name="search" class="form-control rounded-pill"
                           placeholder="Tên đề tài, keyword..."
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>

                <!-- Học kỳ -->
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted mb-1">HỌC KỲ</label>
                    <select name="semester_id" class="form-select rounded-pill">
                        <option value="0">Tất cả học kỳ</option>
                        <?php foreach ($semesters as $sem): ?>
                            <option value="<?= $sem['id'] ?>"
                                <?= (int)($_GET['semester_id'] ?? 0) === (int)$sem['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sem['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Trạng thái (ẩn với student vì luôn chỉ thấy approved) -->
                <?php if ($role !== 'student'): ?>
                <div class="col-md-2">
                    <label class="form-label fw-bold small text-muted mb-1">TRẠNG THÁI</label>
                    <select name="status" class="form-select rounded-pill">
                        <option value="">Tất cả</option>
                        <option value="pending"  <?= ($_GET['status'] ?? '') === 'pending'  ? 'selected' : '' ?>>Chờ duyệt</option>
                        <option value="approved" <?= ($_GET['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Đã duyệt</option>
                        <option value="rejected" <?= ($_GET['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Từ chối</option>
                    </select>
                </div>
                <?php endif; ?>

                <!-- Nút lọc -->
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-3 flex-fill fw-bold">
                        <i class="fa fa-search me-1"></i>Lọc
                    </button>
                    <a href="index.php?page=topics" class="btn btn-light rounded-pill px-3">
                        <i class="fa fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="topicsTable">
                    <thead>
                        <tr class="text-muted small">
                            <th class="ps-3">TIÊU ĐỀ</th>
                            <th>KEYWORD</th>
                            <th>HỌC KỲ</th>
                            <th>TRẠNG THÁI</th>
                            <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                <th class="text-end pe-3">THAO TÁC</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($topics)): foreach ($topics as $t): ?>
                            <tr>
                                <!-- Tiêu đề + mô tả -->
                                <td class="ps-3">
                                    <div class="fw-bold"><?= htmlspecialchars($t['title']) ?></div>
                                    <?php if (!empty($t['description'])): ?>
                                        <div class="text-muted small"
                                             style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            <?= htmlspecialchars($t['description']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <!-- Keywords -->
                                <td>
                                    <?php if (!empty($t['keywords'])): ?>
                                        <span class="badge bg-light text-secondary border" style="font-size:11px;">
                                            <?= htmlspecialchars($t['keywords']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Học kỳ -->
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= htmlspecialchars($t['semester']) ?>
                                    </span>
                                </td>

                                <!-- Trạng thái -->
                                <td>
                                    <?php
                                        $s   = $t['status'];
                                        $cls = $s === 'approved' ? 'bg-success'
                                             : ($s === 'rejected' ? 'bg-danger'
                                             : 'bg-warning text-dark');
                                        $lbl = $s === 'approved' ? 'Đã duyệt'
                                             : ($s === 'rejected' ? 'Từ chối'
                                             : 'Chờ duyệt');
                                    ?>
                                    <span class="badge rounded-pill <?= $cls ?> px-3 py-2" style="font-size:11px;">
                                        <?= $lbl ?>
                                    </span>
                                </td>

                                <!-- Thao tác (admin / lecturer) -->
                                <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-1 flex-wrap">

                                        <?php if ($role === 'admin'): ?>
                                            <?php if ($s === 'pending'): ?>
                                                <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved"
                                                   class="btn btn-sm btn-success rounded-pill px-3">Duyệt</a>
                                                <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected"
                                                   class="btn btn-sm btn-danger rounded-pill px-3">Từ chối</a>
                                            <?php elseif ($s === 'approved'): ?>
                                                <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected"
                                                   class="btn btn-sm btn-outline-danger rounded-pill px-3">Từ chối</a>
                                            <?php else: ?>
                                                <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved"
                                                   class="btn btn-sm btn-outline-success rounded-pill px-3">Duyệt lại</a>
                                            <?php endif; ?>
                                            <a href="index.php?page=topic-edit&id=<?= $t['id'] ?>"
                                               class="btn btn-sm btn-outline-warning rounded-pill px-3">Sửa</a>
                                            <a href="index.php?page=topic-delete&id=<?= $t['id'] ?>"
                                               class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                               onclick="return confirm('Xác nhận xóa đề tài này?')">Xóa</a>

                                        <?php elseif ($role === 'lecturer'): ?>
                                            <?php if ($s === 'pending'): ?>
                                                <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved"
                                                   class="btn btn-sm btn-success rounded-pill px-3">Duyệt</a>
                                                <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected"
                                                   class="btn btn-sm btn-danger rounded-pill px-3">Từ chối</a>
                                            <?php else: ?>
                                                <span class="text-muted small">Đã xử lý</span>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="<?= in_array($role, ['admin', 'lecturer']) ? 5 : 4 ?>"
                                    class="text-center py-5 text-muted">
                                    <i class="fa fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                    <?= $role === 'student' ? 'Chưa có đề tài nào được duyệt.' : 'Chưa có đề tài nào.' ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
