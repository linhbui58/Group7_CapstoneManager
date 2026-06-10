<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
    .notif-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 12px rgba(0,0,0,.04);
        transition: box-shadow .15s, transform .15s;
        position: relative;
        overflow: hidden;
    }
    .notif-card:hover {
        box-shadow: 0 8px 28px rgba(99,102,241,.1);
        transform: translateY(-1px);
    }
    .notif-card.unread::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #6366f1, #4f46e5);
        border-radius: 4px 0 0 4px;
    }
    .notif-card.read::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px;
        background: #e2e8f0;
        border-radius: 4px 0 0 4px;
    }
    .notif-icon {
        width: 44px; height: 44px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .type-system      { background: #eff6ff; color: #2563eb; }
    .type-registration{ background: #f0fdf4; color: #16a34a; }
    .type-score       { background: #fff7ed; color: #ea580c; }
    .type-submission  { background: #fdf4ff; color: #9333ea; }
    .type-milestone   { background: #fef2f2; color: #dc2626; }
    .type-default     { background: #f1f5f9; color: #64748b; }

    .type-badge {
        font-size: 10px; font-weight: 700;
        letter-spacing: .5px; text-transform: uppercase;
        padding: 3px 10px; border-radius: 50px;
    }
    .action-btn {
        width: 32px; height: 32px; border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .8rem; border: none; transition: all .15s; cursor: pointer;
    }
    .action-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,.1); }
    .topbar-card {
        background: #fff; border-radius: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 12px rgba(0,0,0,.04);
    }
    .unread-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #6366f1; flex-shrink: 0; margin-top: 6px;
    }
    .filter-btn {
        font-size: 12px; font-weight: 600; border-radius: 50px;
        padding: 6px 16px; border: 1.5px solid #e2e8f0;
        background: #fff; color: #64748b; cursor: pointer;
        transition: all .15s;
    }
    .filter-btn.active, .filter-btn:hover {
        background: #6366f1; color: #fff; border-color: #6366f1;
    }
</style>

<div class="main-content" style="padding: 32px; background: #f4f7fe; min-height: 100vh;">

    <!-- TOPBAR -->
    <div class="topbar-card p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1" style="color:#0f172a; font-size:22px">
                <i class="fa-solid fa-bell me-2 text-primary"></i>Thông Báo
            </h2>
            <p class="text-muted small mb-0">Cập nhật tiến độ và hoạt động đồ án của bạn</p>
        </div>
        <?php
            $unreadCount = count(array_filter($notifications ?? [], fn($n) => !$n['is_read']));
        ?>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <?php if ($unreadCount > 0): ?>
                <span class="badge rounded-pill px-3 py-2 fw-bold"
                      style="background:#eff6ff;color:#2563eb;font-size:12px">
                    <?= $unreadCount ?> chưa đọc
                </span>
                <a href="index.php?page=notification-read-all"
                   class="btn btn-light rounded-pill px-3 fw-bold"
                   style="font-size:13px;border:1.5px solid #e2e8f0">
                    <i class="fa-solid fa-check-double me-1"></i>Đánh dấu tất cả đã đọc
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- ALERTS -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- FILTER TABS -->
    <?php if (!empty($notifications)): ?>
    <div class="d-flex gap-2 mb-4 flex-wrap" id="filterTabs">
        <button class="filter-btn active" data-filter="all">
            Tất cả <span class="ms-1 opacity-75">(<?= count($notifications) ?>)</span>
        </button>
        <button class="filter-btn" data-filter="unread">
            Chưa đọc <span class="ms-1 opacity-75">(<?= $unreadCount ?>)</span>
        </button>
        <?php
            $types = array_unique(array_column($notifications, 'type'));
            foreach ($types as $t):
        ?>
        <button class="filter-btn" data-filter="<?= htmlspecialchars($t) ?>">
            <?= htmlspecialchars(ucfirst($t)) ?>
        </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- NOTIFICATION LIST -->
    <?php if (!empty($notifications)): ?>
        <div class="d-flex flex-column gap-3" id="notifList">
            <?php foreach ($notifications as $n): ?>
                <?php
                    $isRead  = (bool)$n['is_read'];
                    $type    = $n['type'] ?? 'system';
                    $typeClass = match($type) {
                        'system'       => 'type-system',
                        'registration' => 'type-registration',
                        'score'        => 'type-score',
                        'submission'   => 'type-submission',
                        'milestone'    => 'type-milestone',
                        default        => 'type-default',
                    };
                    $typeIcon = match($type) {
                        'system'       => 'fa-gear',
                        'registration' => 'fa-file-signature',
                        'score'        => 'fa-star',
                        'submission'   => 'fa-upload',
                        'milestone'    => 'fa-flag',
                        default        => 'fa-bell',
                    };
                ?>
                <div class="notif-card <?= $isRead ? 'read' : 'unread' ?> p-4"
                     data-type="<?= htmlspecialchars($type) ?>"
                     data-read="<?= $isRead ? '1' : '0' ?>">
                    <div class="d-flex align-items-start gap-3">

                        <!-- Icon -->
                        <div class="notif-icon <?= $typeClass ?>">
                            <i class="fa-solid <?= $typeIcon ?>"></i>
                        </div>

                        <!-- Content -->
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <?php if (!$isRead): ?>
                                    <div class="unread-dot"></div>
                                <?php endif; ?>
                                <span class="type-badge <?= $typeClass ?>">
                                    <?= htmlspecialchars(strtoupper($type)) ?>
                                </span>
                                <?php if (!empty($n['email'])): ?>
                                    <span class="text-muted" style="font-size:11px">
                                        <i class="fa-solid fa-user me-1"></i><?= htmlspecialchars($n['email']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="mb-0 <?= $isRead ? 'text-muted' : 'fw-semibold' ?>"
                               style="color:<?= $isRead ? '#64748b' : '#1e293b' ?>; font-size:14px; line-height:1.5">
                                <?= htmlspecialchars($n['content']) ?>
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-1 flex-shrink-0">
                            <?php if (!$isRead): ?>
                                <a href="index.php?page=notification-read&id=<?= $n['id'] ?>"
                                   class="action-btn text-white"
                                   style="background:#6366f1"
                                   title="Đánh dấu đã đọc">
                                    <i class="fa-solid fa-check"></i>
                                </a>
                            <?php endif; ?>
                            <a href="index.php?page=notification-delete&id=<?= $n['id'] ?>"
                               class="action-btn text-white btn-delete"
                               style="background:#ef4444"
                               title="Xóa thông báo">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <!-- EMPTY STATE -->
        <div class="text-center py-5 mt-4">
            <div style="width:80px;height:80px;border-radius:24px;background:#eff6ff;color:#6366f1;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 20px">
                <i class="fa-solid fa-bell-slash"></i>
            </div>
            <h5 class="fw-bold" style="color:#1e293b">Không có thông báo nào</h5>
            <p class="text-muted small">Khi có cập nhật mới, chúng sẽ xuất hiện tại đây.</p>
        </div>
    <?php endif; ?>

</div>

<script>
// Filter tabs
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        document.querySelectorAll('#notifList .notif-card').forEach(card => {
            if (filter === 'all') {
                card.style.display = '';
            } else if (filter === 'unread') {
                card.style.display = card.dataset.read === '0' ? '' : 'none';
            } else {
                card.style.display = card.dataset.type === filter ? '' : 'none';
            }
        });
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
