<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
    .detail-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(99,102,241,.07);
        border: 1px solid #f1f5f9;
    }
    .info-row {
        display: flex; align-items: flex-start;
        padding: 16px 0;
        border-bottom: 1px solid #f8fafc;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label {
        font-size: 11px; font-weight: 700;
        color: #94a3b8; text-transform: uppercase;
        letter-spacing: .6px; min-width: 160px; flex-shrink: 0;
    }
    .info-value { font-size: 14px; color: #1e293b; font-weight: 500; }
    .status-pill {
        font-size: 11px; font-weight: 700;
        letter-spacing: .5px; text-transform: uppercase;
        padding: 5px 16px; border-radius: 50px;
    }
    .status-submitted { background:#eff6ff; color:#2563eb; }
    .status-reviewed  { background:#f0fdf4; color:#16a34a; }
    .status-rejected  { background:#fef2f2; color:#dc2626; }
</style>

<div class="main-content" style="padding: 32px; background: #f4f7fe; min-height: 100vh;">

    <div class="mb-4">
        <a href="index.php?page=submissions"
           class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> QUAY LẠI DANH SÁCH
        </a>
        <h2 class="fw-bold mt-2 mb-0" style="color:#0f172a">Chi Tiết Bài Nộp</h2>
        <p class="text-muted small mt-1">#<?= $submission['id'] ?></p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="detail-card p-5">

                <!-- Header -->
                <div class="d-flex align-items-center gap-3 mb-4 pb-4" style="border-bottom:1px solid #f1f5f9">
                    <div style="width:52px;height:52px;border-radius:16px;background:#eff6ff;color:#6366f1;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#1e293b;font-size:16px">
                            <?= htmlspecialchars(ucfirst($submission['milestone_title'] ?? 'Submission')) ?>
                        </div>
                        <div class="text-muted small">
                            <?= htmlspecialchars($submission['student_name'] ?? '—') ?>
                        </div>
                    </div>
                    <?php
                        $status = $submission['status'] ?? 'submitted';
                        $sc = match($status) {
                            'submitted' => 'status-submitted',
                            'reviewed'  => 'status-reviewed',
                            'rejected'  => 'status-rejected',
                            default     => '',
                        };
                        $sl = match($status) {
                            'submitted' => 'Chờ duyệt',
                            'reviewed'  => 'Đã duyệt',
                            'rejected'  => 'Từ chối',
                            default     => ucfirst($status),
                        };
                    ?>
                    <span class="status-pill <?= $sc ?> ms-auto"><?= $sl ?></span>
                </div>

                <!-- Info rows -->
                <div class="info-row">
                    <span class="info-label">ID</span>
                    <span class="info-value">#<?= $submission['id'] ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Sinh viên</span>
                    <span class="info-value"><?= htmlspecialchars($submission['student_name'] ?? '—') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Cột mốc</span>
                    <span class="info-value"><?= htmlspecialchars(ucfirst($submission['milestone_title'] ?? '—')) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trạng thái</span>
                    <span class="info-value">
                        <span class="status-pill <?= $sc ?>"><?= $sl ?></span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Thời gian nộp</span>
                    <span class="info-value">
                        <?= $submission['submitted_at']
                            ? date('d/m/Y H:i', strtotime($submission['submitted_at']))
                            : '—' ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">File đính kèm</span>
                    <span class="info-value">
                        <?php if (!empty($submission['file_path'])): ?>
                            <?php
                                $ext = strtolower(pathinfo($submission['file_path'], PATHINFO_EXTENSION));
                                $icon = match($ext) {
                                    'pdf'  => ['fa-file-pdf','#ef4444'],
                                    'doc','docx' => ['fa-file-word','#2563eb'],
                                    default => ['fa-file','#64748b'],
                                };
                            ?>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid <?= $icon[0] ?>" style="color:<?= $icon[1] ?>"></i>
                                <span class="text-truncate" style="max-width:220px">
                                    <?= htmlspecialchars(basename($submission['file_path'])) ?>
                                </span>
                                <a href="assets/uploads/<?= htmlspecialchars($submission['file_path']) ?>"
                                   target="_blank"
                                   class="btn btn-sm rounded-pill px-3 fw-bold text-white ms-2"
                                   style="background:#10b981;border:none;font-size:12px">
                                    <i class="fa-solid fa-download me-1"></i>Tải xuống
                                </a>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Không có file</span>
                        <?php endif; ?>
                    </span>
                </div>

            </div>

            <!-- Actions -->
            <div class="mt-4 d-flex gap-3">
                <a href="index.php?page=submissions"
                   class="btn btn-light rounded-pill px-4 fw-bold">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                </a>
                <?php if (in_array($_SESSION['user']['role'], ['admin','lecturer'])): ?>
                    <a href="index.php?page=submission-delete&id=<?= $submission['id'] ?>"
                       class="btn rounded-pill px-4 fw-bold text-white btn-delete"
                       style="background:#ef4444;border:none">
                        <i class="fa-solid fa-trash me-2"></i>Xóa bài nộp
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
