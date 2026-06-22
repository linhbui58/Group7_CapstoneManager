<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<?php
$role       = $_SESSION['user']['role'];
$activeTab  = $_GET['tab'] ?? 'topics';
?>

<style>
/* ── Tab nav đẹp hơn ── */
#mainTabs .nav-link {
    color: #64748b; font-weight: 600; font-size: 14px;
    border: none; border-bottom: 3px solid transparent;
    padding: 10px 20px; border-radius: 0;
    transition: all .2s;
}
#mainTabs .nav-link:hover { color: #4f46e5; background: #f8f9ff; }
#mainTabs .nav-link.active {
    color: #4f46e5; border-bottom-color: #4f46e5;
    background: transparent;
}

/* ── Table đẹp hơn ── */
.table thead th {
    font-size: 11px !important; font-weight: 700 !important;
    color: #94a3b8 !important; text-transform: uppercase;
    letter-spacing: .6px; background: #fafbff !important;
    border-bottom: 1px solid #f1f5f9 !important;
    padding: 13px 12px !important;
}
.table tbody td { padding: 13px 12px !important; vertical-align: middle; border-color: #f8fafc; }
.table tbody tr:hover td { background: #f8faff; }

/* ── Badge status ── */
.badge.bg-success  { background: #dcfce7 !important; color: #16a34a !important; }
.badge.bg-danger   { background: #fee2e2 !important; color: #dc2626 !important; }
.badge.bg-warning  { background: #fff7ed !important; color: #ea580c !important; }
.badge.bg-primary  { background: #eff6ff !important; color: #2563eb !important; }

/* ── Action buttons ── */
.btn-sm.rounded-circle {
    width: 30px !important; height: 30px !important;
    padding: 0 !important; border-radius: 8px !important;
    display: inline-flex !important; align-items: center !important;
    justify-content: center !important; font-size: 12px !important;
}

/* ── Filter bar ── */
.filter-bar {
    background: #fff; border-radius: 16px;
    padding: 16px 20px; margin-bottom: 20px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
}

/* ── Card container ── */
.main-content .card { box-shadow: 0 4px 20px rgba(0,0,0,.06) !important; }
</style>

<div class="main-content" style="padding: 28px 32px; background-color: #f4f7fe; min-height: 100vh;">

    <!-- ── Page Header ── -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Đề Tài & Đăng Ký</h2>
            <p class="text-muted small mb-0">
                <?php if ($role === 'admin'): ?>Quản lý toàn bộ đề tài và đăng ký trong hệ thống
                <?php elseif ($role === 'lecturer'): ?>Đề tài và đăng ký liên quan đến bạn
                <?php else: ?>Xem đề tài và theo dõi đăng ký của bạn
                <?php endif; ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <?php if (in_array($role, ['admin', 'student'])): ?>
                <a href="index.php?page=topic-create"
                   class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    <i class="fa fa-plus me-1"></i>
                    <?= $role === 'admin' ? 'Thêm đề tài' : 'Đề xuất đề tài' ?>
                </a>
            <?php endif; ?>
            <?php if ($role === 'student'): ?>
                <a href="index.php?page=registration-create"
                   class="btn btn-dark rounded-pill px-4 shadow-sm fw-bold">
                    <i class="fa fa-file-signature me-1"></i> Đăng ký mới
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Flash Messages ── -->
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

    <!-- ── Tabs ── -->
    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">

        <!-- Tab Nav -->
        <div class="px-4 pt-3" style="background: #fff; border-bottom: 1px solid #f0f0f0;">
            <ul class="nav nav-tabs border-0 gap-1" id="mainTabs">
                <li class="nav-item">
                    <a class="nav-link fw-semibold px-4 <?= $activeTab === 'topics' ? 'active' : '' ?>"
                       href="index.php?page=topic-management&tab=topics"
                       style="border-radius: 10px 10px 0 0;">
                        <i class="fa fa-book me-2"></i>Đề Tài
                        <span class="badge bg-primary bg-opacity-10 text-primary ms-1" style="font-size:10px;">
                            <?= count($topics) ?>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold px-4 <?= $activeTab === 'registrations' ? 'active' : '' ?>"
                       href="index.php?page=topic-management&tab=registrations"
                       style="border-radius: 10px 10px 0 0;">
                        <i class="fa fa-file-signature me-2"></i>Đăng Ký
                        <span class="badge bg-warning bg-opacity-25 text-warning ms-1" style="font-size:10px;">
                            <?= count($registrations) ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">

            <!-- ════════════════════════════════════
                 TAB 1: TOPICS
            ════════════════════════════════════ -->
            <?php if ($activeTab === 'topics'): ?>

                <!-- Filter bar -->
                <div class="card border-0 bg-light mb-4" style="border-radius: 14px;">
                    <div class="card-body p-3">
                        <form method="GET" action="index.php" class="row g-2 align-items-end">
                            <input type="hidden" name="page" value="topic-management">
                            <input type="hidden" name="tab"  value="topics">

                            <div class="col-md-<?= $role === 'student' ? '7' : '5' ?>">
                                <label class="form-label fw-bold small text-muted mb-1">TÌM KIẾM</label>
                                <input type="text" name="search" class="form-control rounded-pill"
                                       placeholder="Tên đề tài, keyword..."
                                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>
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
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary rounded-pill px-3 flex-fill fw-bold">
                                    <i class="fa fa-search me-1"></i>Lọc
                                </button>
                                <a href="index.php?page=topic-management&tab=topics" class="btn btn-light rounded-pill px-3">
                                    <i class="fa fa-rotate-left"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Topics Table -->
                <?php if ($role === 'student'): 
                    $myTopics = array_filter($topics, fn($t) => isset($t['created_by']) && $t['created_by'] == $_SESSION['user']['id']);
                    $generalTopics = array_filter($topics, fn($t) => !isset($t['created_by']) || $t['created_by'] != $_SESSION['user']['id']);
                ?>
                    <!-- Bảng đề tài cá nhân -->
                    <h5 class="fw-bold mb-3 text-primary"><i class="fa fa-user-edit me-2"></i>Đề tài cá nhân</h5>
                    <div class="table-responsive mb-5 shadow-sm rounded-3">
                        <table class="table table-hover align-middle mb-0" id="myTopicsTable">
                            <thead class="table-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-3">Tiêu Đề</th>
                                    <th>Học Kỳ</th>
                                    <th class="text-center">Trạng Thái</th>
                                    <th class="text-center">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($myTopics)): foreach ($myTopics as $t): 
                                    $ts   = $t['status'];
                                    $tcls = $ts === 'approved' ? 'bg-success' : ($ts === 'rejected' ? 'bg-danger' : 'bg-warning text-dark');
                                    $tlbl = $ts === 'approved' ? 'Đã duyệt'  : ($ts === 'rejected' ? 'Từ chối'   : 'Chờ duyệt');
                                    $tico = $ts === 'approved' ? 'fa-circle-check' : ($ts === 'rejected' ? 'fa-circle-xmark' : 'fa-clock');
                                ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold"><?= htmlspecialchars($t['title']) ?></div>
                                        <?php if (!empty($t['description'])): ?>
                                            <div class="text-muted small" style="max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                <?= htmlspecialchars($t['description']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border" style="font-size:11px;">
                                            <i class="fa fa-calendar-alt me-1 text-muted"></i><?= htmlspecialchars($t['semester']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill <?= $tcls ?> px-3 py-2" style="font-size:11px;">
                                            <i class="fa <?= $tico ?> me-1"></i><?= $tlbl ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($ts === 'pending'): ?>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="index.php?page=topic-edit&id=<?= $t['id'] ?>"
                                               class="btn btn-sm btn-outline-warning rounded-circle" data-bs-toggle="tooltip" title="Sửa" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fa fa-pen"></i>
                                            </a>
                                            <form action="index.php?page=topic-delete&id=<?= $t['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa đề tài này?');">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle border-0" data-bs-toggle="tooltip" title="Xóa" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">Bạn chưa đề xuất đề tài nào.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Bảng đề tài chung -->
                    <h5 class="fw-bold mb-3 text-success"><i class="fa fa-globe me-2"></i>Ngân hàng đề tài chung</h5>
                    <div class="table-responsive shadow-sm rounded-3">
                        <table class="table table-hover align-middle mb-0" id="generalTopicsTable">
                            <thead class="table-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-3">Tiêu Đề</th>
                                    <th>Học Kỳ</th>
                                    <th class="text-center">Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($generalTopics)): foreach ($generalTopics as $t): 
                                    $ts   = $t['status'];
                                    $tcls = $ts === 'approved' ? 'bg-success' : ($ts === 'rejected' ? 'bg-danger' : 'bg-warning text-dark');
                                    $tlbl = $ts === 'approved' ? 'Đã duyệt'  : ($ts === 'rejected' ? 'Từ chối'   : 'Chờ duyệt');
                                    $tico = $ts === 'approved' ? 'fa-circle-check' : ($ts === 'rejected' ? 'fa-circle-xmark' : 'fa-clock');
                                ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold"><?= htmlspecialchars($t['title']) ?></div>
                                        <?php if (!empty($t['description'])): ?>
                                            <div class="text-muted small" style="max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                <?= htmlspecialchars($t['description']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border" style="font-size:11px;">
                                            <i class="fa fa-calendar-alt me-1 text-muted"></i><?= htmlspecialchars($t['semester']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill <?= $tcls ?> px-3 py-2" style="font-size:11px;">
                                            <i class="fa <?= $tico ?> me-1"></i><?= $tlbl ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr><td colspan="3" class="text-center py-4 text-muted">Không có đề tài chung nào.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="topicsTable">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-2">Tiêu Đề</th>
                                <th>Học Kỳ</th>
                                <th class="text-center">Trạng Thái</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topics)): foreach ($topics as $t):
                                $ts   = $t['status'];
                                $tcls = $ts === 'approved' ? 'bg-success' : ($ts === 'rejected' ? 'bg-danger' : 'bg-warning text-dark');
                                $tlbl = $ts === 'approved' ? 'Đã duyệt'  : ($ts === 'rejected' ? 'Từ chối'   : 'Chờ duyệt');
                                $tico = $ts === 'approved' ? 'fa-circle-check' : ($ts === 'rejected' ? 'fa-circle-xmark' : 'fa-clock');
                            ?>
                                <tr>
                                    <td class="ps-2">
                                        <div class="fw-semibold"><?= htmlspecialchars($t['title']) ?></div>
                                        <?php if (!empty($t['description'])): ?>
                                            <div class="text-muted small"
                                                 style="max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                <?= htmlspecialchars($t['description']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border" style="font-size:11px;">
                                            <i class="fa fa-calendar-alt me-1 text-muted"></i>
                                            <?= htmlspecialchars($t['semester']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill <?= $tcls ?> px-3 py-2" style="font-size:11px;">
                                            <i class="fa <?= $tico ?> me-1"></i><?= $tlbl ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                                            <?php if ($role === 'admin'): ?>
                                                <?php if ($ts === 'pending'): ?>
                                                    <form action="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved" method="POST" class="d-inline" onsubmit="return confirm('Duyệt đề tài này?');">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <button type="submit" class="btn btn-sm btn-success rounded-circle shadow-sm border-0" data-bs-toggle="tooltip" title="Duyệt" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="fa fa-check"></i>
    </button>
</form>
                                                    <form action="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected" method="POST" class="d-inline" onsubmit="return confirm('Từ chối đề tài này?');">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle border-0" data-bs-toggle="tooltip" title="Từ chối" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="fa fa-xmark"></i>
    </button>
</form>
                                                <?php elseif ($ts === 'approved'): ?>
                                                    <form action="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected" method="POST" class="d-inline" onsubmit="return confirm('Thu hồi duyệt đề tài này?');">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle border-0" data-bs-toggle="tooltip" title="Thu hồi" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="fa fa-rotate-left"></i>
    </button>
</form>
                                                <?php else: ?>
                                                    <form action="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved" method="POST" class="d-inline" onsubmit="return confirm('Duyệt lại đề tài này?');">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <button type="submit" class="btn btn-sm btn-outline-success rounded-circle border-0" data-bs-toggle="tooltip" title="Duyệt lại" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="fa fa-rotate-left"></i>
    </button>
</form>
                                                <?php endif; ?>
                                                <a href="index.php?page=topic-edit&id=<?= $t['id'] ?>"
                                                   class="btn btn-sm btn-outline-warning rounded-circle" data-bs-toggle="tooltip" title="Sửa" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <form action="index.php?page=topic-delete&id=<?= $t['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa đề tài này?');">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle border-0" data-bs-toggle="tooltip" title="Xóa" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="fa fa-trash"></i>
    </button>
</form>
                                            <?php elseif ($role === 'lecturer'): ?>
                                                <?php if ($ts === 'pending'): ?>
                                                    <form action="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved" method="POST" class="d-inline" onsubmit="return confirm('Duyệt đề tài này?');">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <button type="submit" class="btn btn-sm btn-success rounded-circle shadow-sm border-0" data-bs-toggle="tooltip" title="Duyệt" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="fa fa-check"></i>
    </button>
</form>
                                                    <form action="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected" method="POST" class="d-inline" onsubmit="return confirm('Từ chối đề tài này?');">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle border-0" data-bs-toggle="tooltip" title="Từ chối" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
        <i class="fa fa-xmark"></i>
    </button>
</form>
                                                <?php else: ?>
                                                    <span class="text-muted small">Đã xử lý</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                     <td colspan="5"
                                        class="text-center py-5 text-muted">
                                        <i class="fa fa-inbox fa-2x mb-3 d-block opacity-25"></i>
                                        <div class="fw-semibold">Chưa có đề tài nào</div>
                                        <div class="small mt-1 text-muted">
                                            Thêm đề tài mới để bắt đầu.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

            <?php endif; /* end tab topics */ ?>


            <!-- ════════════════════════════════════
                 TAB 2: REGISTRATIONS
            ════════════════════════════════════ -->
            <?php if ($activeTab === 'registrations'): ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="registrationsTable">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-2">Đề Tài</th>
                                <th>Học Kỳ</th>
                                <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <th>Sinh Viên</th>
                                <?php endif; ?>
                                <?php if ($role === 'admin'): ?>
                                    <th>GV Mong Muốn</th>
                                <?php endif; ?>
                                <th>Trạng Thái</th>
                                <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <th class="text-center">Thao Tác</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($registrations)): foreach ($registrations as $r):
                                $s         = $r['status'];
                                $badgeCls  = $s === 'approved' ? 'bg-success' : ($s === 'rejected' ? 'bg-danger' : ($s === 'registered' ? 'bg-primary' : 'bg-warning text-dark'));
                                $badgeLbl  = $s === 'approved' ? 'Đã duyệt'  : ($s === 'rejected' ? 'Từ chối'   : ($s === 'registered' ? 'Đã đăng ký' : 'Chờ duyệt'));
                                $badgeIcon = $s === 'approved' ? 'fa-check' : ($s === 'rejected' ? 'fa-xmark' : ($s === 'registered' ? 'fa-check-double' : 'fa-clock'));
                            ?>
                                <tr>
                                    <!-- Đề tài -->
                                    <td class="ps-2">
                                        <div class="fw-semibold"><?= htmlspecialchars($r['topic_title']) ?></div>
                                        <?php if (!empty($r['keywords'])): ?>
                                            <div class="mt-1">
                                                <?php foreach (explode(',', $r['keywords']) as $kw): ?>
                                                    <span class="badge bg-light text-secondary border me-1" style="font-size:10px;">
                                                        <?= htmlspecialchars(trim($kw)) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Học kỳ -->
                                    <td>
                                        <span class="badge bg-light text-dark border" style="font-size:11px;">
                                            <i class="fa fa-calendar-alt me-1 text-muted"></i>
                                            <?= htmlspecialchars($r['semester_name']) ?>
                                        </span>
                                    </td>

                                    <!-- Sinh viên -->
                                    <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                                 style="width:32px; height:32px; flex-shrink:0;">
                                                <i class="fa fa-user-graduate text-primary" style="font-size:13px;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold small"><?= htmlspecialchars($r['student_name'] ?? 'N/A') ?></div>
                                                <div class="text-muted" style="font-size:10px;">ID: <?= $r['student_id'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <?php endif; ?>

                                    <!-- GV mong muốn -->
                                    <?php if ($role === 'admin'): ?>
                                    <td>
                                        <?php if (!empty($r['lecturer_name'])): ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center"
                                                     style="width:28px; height:28px; flex-shrink:0;">
                                                    <i class="fa fa-chalkboard-teacher text-info" style="font-size:11px;"></i>
                                                </div>
                                                <span class="small"><?= htmlspecialchars($r['lecturer_name']) ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small fst-italic">Không chỉ định</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>

                                    <!-- Trạng thái -->
                                    <td>
                                        <span class="badge rounded-pill <?= $badgeCls ?> px-3 py-2" style="font-size:11px;">
                                            <i class="fa <?= $badgeIcon ?> me-1"></i><?= $badgeLbl ?>
                                        </span>
                                    </td>

                                    <!-- Thao tác -->
                                    <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <td class="text-center">
                                            <?php if ($s === 'pending'): ?>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <form action="index.php?page=registration-status&id=<?= $r['id'] ?>&status=approved" method="POST" class="d-inline" onsubmit="return confirm('Duyệt đăng ký này?');">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <button type="submit" class="btn btn-sm btn-success rounded-pill px-2 shadow-sm border-0" >
            <i class="fa fa-check me-1"></i>Duyệt
        </button>
    </form>
                                                    <form action="index.php?page=registration-status&id=<?= $r['id'] ?>&status=rejected" method="POST" class="d-inline" onsubmit="return confirm('Từ chối đăng ký này?');">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 border-0" >
            <i class="fa fa-xmark me-1"></i>Từ chối
        </button>
    </form>
                                                </div>
                                            <?php elseif ($s === 'approved'): ?>
                                                <form action="index.php?page=registration-status&id=<?= $r['id'] ?>&status=rejected" method="POST" class="d-inline" onsubmit="return confirm('Thu hồi duyệt đăng ký này?');">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 border-0" >
            <i class="fa fa-rotate-left me-1"></i>Thu hồi
        </button>
    </form>
                                            <?php elseif ($s === 'rejected'): ?>
                                                <form action="index.php?page=registration-status&id=<?= $r['id'] ?>&status=approved" method="POST" class="d-inline" onsubmit="return confirm('Duyệt lại đăng ký này?');">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-2 border-0" >
            <i class="fa fa-rotate-left me-1"></i>Duyệt lại
        </button>
    </form>
                                            <?php else: ?>
                                                <span class="text-muted small">Đã đăng ký</span>
                                            <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="<?= $role === 'admin' ? 6 : ($role === 'lecturer' ? 5 : 3) ?>"
                                        class="text-center py-5 text-muted">
                                        <i class="fa fa-folder-open fa-2x mb-3 d-block opacity-25"></i>
                                        <div class="fw-semibold">Chưa có đăng ký nào</div>
                                        <div class="small mt-1">
                                            <?= $role === 'student'
                                                ? 'Nhấn "Đăng ký mới" để chọn đề tài.'
                                                : 'Chưa có sinh viên nào đăng ký.' ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; /* end tab registrations */ ?>

        </div><!-- /.card-body -->
    </div><!-- /.card -->
</div><!-- /.main-content -->

<script>
$(document).ready(function () {
    $('[data-bs-toggle="tooltip"]').tooltip({ trigger: 'hover' });

    const dtConfig = {
        retrieve  : true,
        language  : {
            search            : '',
            searchPlaceholder : 'Tìm kiếm...',
            emptyTable        : 'Không có dữ liệu.',
            lengthMenu        : 'Hiển thị _MENU_ dòng',
            info              : 'Hiển thị _START_ – _END_ / _TOTAL_',
            infoEmpty         : 'Không có dữ liệu',
            paginate: {
                previous : '<i class="fa fa-chevron-left"></i>',
                next     : '<i class="fa fa-chevron-right"></i>'
            }
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small text-muted"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    };

    $.fn.dataTable.ext.errMode = 'none';

    if ($('#topicsTable').length) {
        $('#topicsTable').DataTable(dtConfig);
    }
    if ($('#myTopicsTable').length) {
        $('#myTopicsTable').DataTable(dtConfig);
    }
    if ($('#generalTopicsTable').length) {
        $('#generalTopicsTable').DataTable(dtConfig);
    }
    if ($('#registrationsTable').length) {
        $('#registrationsTable').DataTable(dtConfig);
    }
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
