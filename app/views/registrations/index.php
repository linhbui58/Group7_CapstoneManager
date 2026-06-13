<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<?php $role = $_SESSION['user']['role']; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc; min-height: 100vh;">

    <!-- ── Page Header ── -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Quản Lý Đăng Ký</h2>
            <p class="text-muted small mb-0">
                <?php if ($role === 'admin'): ?>
                    Tất cả đăng ký đề tài trong hệ thống
                <?php elseif ($role === 'lecturer'): ?>
                    Sinh viên đăng ký chọn bạn làm giảng viên hướng dẫn
                <?php else: ?>
                    Đề tài bạn đã đăng ký
                <?php endif; ?>
            </p>
        </div>
        <?php if ($role === 'student'): ?>
            <a href="index.php?page=registration-create"
               class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="fa fa-plus me-1"></i> Đăng ký mới
            </a>
        <?php endif; ?>
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

    <!-- ── Table Card ── -->
    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="registrationsTable">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th class="ps-3">Đề Tài</th>
                            <th>Học Kỳ</th>
                            <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                <th>Sinh Viên</th>
                            <?php endif; ?>
                            <?php if ($role === 'admin'): ?>
                                <th>GV Mong Muốn</th>
                            <?php endif; ?>
                            <th>Trạng Thái</th>
                            <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                <th class="text-center pe-3">Thao Tác</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($registrations)): foreach ($registrations as $r):
                            $s   = $r['status'];
                            $badgeCls = $s === 'approved' ? 'bg-success'
                                      : ($s === 'rejected' ? 'bg-danger'
                                      : 'bg-warning text-dark');
                            $badgeLbl = $s === 'approved' ? 'Đã duyệt'
                                      : ($s === 'rejected' ? 'Từ chối'
                                      : 'Chờ duyệt');
                            $badgeIcon = $s === 'approved' ? 'fa-circle-check'
                                       : ($s === 'rejected' ? 'fa-circle-xmark'
                                       : 'fa-clock');
                        ?>
                            <tr>
                                <!-- Đề tài -->
                                <td class="ps-3">
                                    <div class="fw-semibold"><?= htmlspecialchars($r['topic_title']) ?></div>
                                    <?php if (!empty($r['keywords'])): ?>
                                        <div class="mt-1">
                                            <?php foreach (explode(',', $r['keywords']) as $kw): ?>
                                                <span class="badge bg-light text-secondary border me-1"
                                                      style="font-size:10px; font-weight:500;">
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

                                <!-- Sinh viên (admin / lecturer) -->
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

                                <!-- GV mong muốn (admin) -->
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
                                    <span class="badge rounded-pill <?= $badgeCls ?> px-3 py-2"
                                          style="font-size:11px; letter-spacing:.3px;">
                                        <i class="fa <?= $badgeIcon ?> me-1"></i><?= $badgeLbl ?>
                                    </span>
                                </td>

                                <!-- Thao tác (admin / lecturer) -->
                                <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                <td class="text-center pe-3">
                                    <?php if ($s === 'pending'): ?>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=approved"
                                               class="btn btn-sm btn-success rounded-pill px-3 shadow-sm"
                                               onclick="return confirm('Duyệt đăng ký này?')"
                                               data-bs-toggle="tooltip" title="Duyệt đăng ký">
                                                <i class="fa fa-check me-1"></i>Duyệt
                                            </a>
                                            <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=rejected"
                                               class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                               onclick="return confirm('Từ chối đăng ký này?')"
                                               data-bs-toggle="tooltip" title="Từ chối đăng ký">
                                                <i class="fa fa-xmark me-1"></i>Từ chối
                                            </a>
                                        </div>
                                    <?php elseif ($s === 'approved'): ?>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=rejected"
                                               class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                               onclick="return confirm('Thu hồi duyệt và từ chối đăng ký này?')"
                                               data-bs-toggle="tooltip" title="Thu hồi duyệt">
                                                <i class="fa fa-rotate-left me-1"></i>Thu hồi
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=approved"
                                               class="btn btn-sm btn-outline-success rounded-pill px-3"
                                               onclick="return confirm('Duyệt lại đăng ký này?')"
                                               data-bs-toggle="tooltip" title="Duyệt lại">
                                                <i class="fa fa-rotate-left me-1"></i>Duyệt lại
                                            </a>
                                        </div>
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
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Khởi tạo tooltips
    $('[data-bs-toggle="tooltip"]').tooltip({ trigger: 'hover' });

    // DataTable
    $.fn.dataTable.ext.errMode = 'none';
    if ($.fn.DataTable.isDataTable('#registrationsTable')) {
        $('#registrationsTable').DataTable().destroy();
    }
    $('#registrationsTable').DataTable({
        retrieve : true,
        language : {
            search              : '',
            searchPlaceholder   : 'Tìm kiếm...',
            emptyTable          : 'Chưa có dữ liệu đăng ký nào.',
            lengthMenu          : 'Hiển thị _MENU_ dòng',
            info                : 'Hiển thị _START_ – _END_ / _TOTAL_ đăng ký',
            infoEmpty           : 'Không có dữ liệu',
            paginate: {
                previous : '<i class="fa fa-chevron-left"></i>',
                next     : '<i class="fa fa-chevron-right"></i>'
            }
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small text-muted"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
