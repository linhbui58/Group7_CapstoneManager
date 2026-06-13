<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<?php
$role       = $_SESSION['user']['role'];
$activeTab  = $_GET['tab'] ?? 'topics';
?>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe; min-height: 100vh;">

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
                   class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
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
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="topicsTable">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-2">Tiêu Đề</th>
                                <th>Keyword</th>
                                <th>Học Kỳ</th>
                                <th>Trạng Thái</th>
                                <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <th class="text-center">Thao Tác</th>
                                <?php endif; ?>
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
                                        <?php if (!empty($t['keywords'])): ?>
                                            <?php foreach (explode(',', $t['keywords']) as $kw): ?>
                                                <span class="badge bg-light text-secondary border me-1" style="font-size:10px;">
                                                    <?= htmlspecialchars(trim($kw)) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border" style="font-size:11px;">
                                            <i class="fa fa-calendar-alt me-1 text-muted"></i>
                                            <?= htmlspecialchars($t['semester']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill <?= $tcls ?> px-3 py-2" style="font-size:11px;">
                                            <i class="fa <?= $tico ?> me-1"></i><?= $tlbl ?>
                                        </span>
                                    </td>
                                    <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                                            <?php if ($role === 'admin'): ?>
                                                <?php if ($ts === 'pending'): ?>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved"
                                                       class="btn btn-sm btn-success rounded-pill px-3"
                                                       onclick="return confirm('Duyệt đề tài này?')">
                                                        <i class="fa fa-check me-1"></i>Duyệt
                                                    </a>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected"
                                                       class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                       onclick="return confirm('Từ chối đề tài này?')">
                                                        <i class="fa fa-xmark me-1"></i>Từ chối
                                                    </a>
                                                <?php elseif ($ts === 'approved'): ?>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected"
                                                       class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                       onclick="return confirm('Thu hồi duyệt đề tài này?')">
                                                        <i class="fa fa-rotate-left me-1"></i>Thu hồi
                                                    </a>
                                                <?php else: ?>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved"
                                                       class="btn btn-sm btn-outline-success rounded-pill px-3"
                                                       onclick="return confirm('Duyệt lại đề tài này?')">
                                                        <i class="fa fa-rotate-left me-1"></i>Duyệt lại
                                                    </a>
                                                <?php endif; ?>
                                                <a href="index.php?page=topic-edit&id=<?= $t['id'] ?>"
                                                   class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <a href="index.php?page=topic-delete&id=<?= $t['id'] ?>"
                                                   class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                   onclick="return confirm('Xác nhận xóa đề tài này?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php elseif ($role === 'lecturer'): ?>
                                                <?php if ($ts === 'pending'): ?>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved"
                                                       class="btn btn-sm btn-success rounded-pill px-3"
                                                       onclick="return confirm('Duyệt đề tài này?')">
                                                        <i class="fa fa-check me-1"></i>Duyệt
                                                    </a>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected"
                                                       class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                       onclick="return confirm('Từ chối đề tài này?')">
                                                        <i class="fa fa-xmark me-1"></i>Từ chối
                                                    </a>
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
                                        <i class="fa fa-inbox fa-2x mb-3 d-block opacity-25"></i>
                                        <div class="fw-semibold">Chưa có đề tài nào</div>
                                        <div class="small mt-1 text-muted">
                                            <?= $role === 'student' ? 'Chưa có đề tài nào được duyệt.' : 'Thêm đề tài mới để bắt đầu.' ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

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
                                $badgeCls  = $s === 'approved' ? 'bg-success' : ($s === 'rejected' ? 'bg-danger' : 'bg-warning text-dark');
                                $badgeLbl  = $s === 'approved' ? 'Đã duyệt'  : ($s === 'rejected' ? 'Từ chối'   : 'Chờ duyệt');
                                $badgeIcon = $s === 'approved' ? 'fa-circle-check' : ($s === 'rejected' ? 'fa-circle-xmark' : 'fa-clock');
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
                                                <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=approved"
                                                   class="btn btn-sm btn-success rounded-pill px-3 shadow-sm"
                                                   onclick="return confirm('Duyệt đăng ký này?')">
                                                    <i class="fa fa-check me-1"></i>Duyệt
                                                </a>
                                                <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=rejected"
                                                   class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                   onclick="return confirm('Từ chối đăng ký này?')">
                                                    <i class="fa fa-xmark me-1"></i>Từ chối
                                                </a>
                                            </div>
                                        <?php elseif ($s === 'approved'): ?>
                                            <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=rejected"
                                               class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                               onclick="return confirm('Thu hồi duyệt đăng ký này?')">
                                                <i class="fa fa-rotate-left me-1"></i>Thu hồi
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=approved"
                                               class="btn btn-sm btn-outline-success rounded-pill px-3"
                                               onclick="return confirm('Duyệt lại đăng ký này?')">
                                                <i class="fa fa-rotate-left me-1"></i>Duyệt lại
                                            </a>
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
                                                ? 'Nhấn "Đăng ký mới" để đăng ký đề tài.'
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
    if ($('#registrationsTable').length) {
        $('#registrationsTable').DataTable(dtConfig);
    }
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
