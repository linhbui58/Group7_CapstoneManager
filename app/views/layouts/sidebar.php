<?php $currentPage = $_GET['page'] ?? ''; ?>

<div class="sidebar">
    <div class="sidebar-top">
        <div class="sidebar-header">
            <h2>Capstone Manager</h2>
        </div>

        <ul class="sidebar-menu">

            <!-- Dashboard (tất cả role) -->
            <li>
                <a href="index.php?page=dashboard"
                   class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="fa fa-chart-line"></i> 
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- ── ADMIN ── -->
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                
                <!-- Nhóm: Quản lý người dùng -->
                <li class="menu-group <?= in_array($currentPage, ['users', 'students', 'lecturers']) ? 'open' : '' ?>">
                    <div class="menu-group-toggle">
                        <i class="fa fa-users-gear"></i>
                        <span>Quản lý người dùng</span>
                        <i class="fa fa-chevron-right toggle-icon"></i>
                    </div>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?page=users" class="<?= $currentPage === 'users' ? 'active' : '' ?>">
                                <i class="fa fa-users"></i> <span>Users</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=students" class="<?= $currentPage === 'students' ? 'active' : '' ?>">
                                <i class="fa fa-user-graduate"></i> <span>Students</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=lecturers" class="<?= $currentPage === 'lecturers' ? 'active' : '' ?>">
                                <i class="fa fa-chalkboard-teacher"></i> <span>Lecturers</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Nhóm: Quản lý đề tài -->
                <li class="menu-group <?= in_array($currentPage, ['semesters', 'topic-management', 'topics', 'registrations', 'assignments']) ? 'open' : '' ?>">
                    <div class="menu-group-toggle">
                        <i class="fa fa-book"></i>
                        <span>Quản lý đề tài</span>
                        <i class="fa fa-chevron-right toggle-icon"></i>
                    </div>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?page=semesters" class="<?= $currentPage === 'semesters' ? 'active' : '' ?>">
                                <i class="fa fa-calendar"></i> <span>Semesters</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=topic-management" class="<?= in_array($currentPage, ['topic-management', 'topics', 'registrations']) ? 'active' : '' ?>">
                                <i class="fa fa-book-open"></i> <span>Topics & Đăng Ký</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=assignments" class="<?= $currentPage === 'assignments' ? 'active' : '' ?>">
                                <i class="fa fa-user-check"></i> <span>Assignments</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Nhóm: Tiến độ & Chấm điểm -->
                <li class="menu-group <?= in_array($currentPage, ['milestones', 'submissions', 'scores']) ? 'open' : '' ?>">
                    <div class="menu-group-toggle">
                        <i class="fa fa-tasks"></i>
                        <span>Tiến độ & Điểm</span>
                        <i class="fa fa-chevron-right toggle-icon"></i>
                    </div>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?page=milestones" class="<?= $currentPage === 'milestones' ? 'active' : '' ?>">
                                <i class="fa fa-flag"></i> <span>Milestones</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=submissions" class="<?= $currentPage === 'submissions' ? 'active' : '' ?>">
                                <i class="fa fa-upload"></i> <span>Submissions</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=scores" class="<?= $currentPage === 'scores' ? 'active' : '' ?>">
                                <i class="fa fa-star"></i> <span>Scores</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Nhóm: Hệ thống -->
                <li class="menu-group <?= in_array($currentPage, ['notifications', 'logs']) ? 'open' : '' ?>">
                    <div class="menu-group-toggle">
                        <i class="fa fa-cog"></i>
                        <span>Hệ thống</span>
                        <i class="fa fa-chevron-right toggle-icon"></i>
                    </div>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?page=notifications" class="<?= $currentPage === 'notifications' ? 'active' : '' ?>">
                                <i class="fa fa-bell"></i> <span>Notifications</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=logs" class="<?= $currentPage === 'logs' ? 'active' : '' ?>">
                                <i class="fa fa-clock-rotate-left"></i> <span>System Logs</span>
                            </a>
                        </li>
                    </ul>
                </li>

            <?php endif; ?>

            <!-- ── STUDENT ── -->
            <?php if ($_SESSION['user']['role'] === 'student'): ?>
                <li>
                    <a href="index.php?page=topic-management"
                       class="<?= in_array($currentPage, ['topic-management', 'topics', 'registrations']) ? 'active' : '' ?>">
                        <i class="fa fa-book-open"></i> <span>Topics & Đăng Ký</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=submissions"
                       class="<?= $currentPage === 'submissions' ? 'active' : '' ?>">
                        <i class="fa fa-upload"></i> <span>My Submissions</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=notifications"
                       class="<?= $currentPage === 'notifications' ? 'active' : '' ?>">
                        <i class="fa fa-bell"></i> <span>Notifications</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- ── LECTURER ── -->
            <?php if ($_SESSION['user']['role'] === 'lecturer'): ?>
                <li>
                    <a href="index.php?page=topic-management"
                       class="<?= in_array($currentPage, ['topic-management', 'topics', 'registrations']) ? 'active' : '' ?>">
                        <i class="fa fa-book-open"></i> <span>Topics & Đăng Ký</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=submissions"
                       class="<?= $currentPage === 'submissions' ? 'active' : '' ?>">
                        <i class="fa fa-upload"></i> <span>Submissions</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=scores"
                       class="<?= $currentPage === 'scores' ? 'active' : '' ?>">
                        <i class="fa fa-star"></i> <span>Scores</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=notifications"
                       class="<?= $currentPage === 'notifications' ? 'active' : '' ?>">
                        <i class="fa fa-bell"></i> <span>Notifications</span>
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </div>

    <div class="sidebar-bottom">
        <ul class="sidebar-menu">
            <li class="user-profile-item">
                <div class="user-profile-wrapper">
                    <div class="user-avatar-circle">
                        <?php
                            $email    = $_SESSION['user']['email'] ?? 'User';
                            $username = explode('@', $email)[0];
                            echo strtoupper(substr($username, 0, 1));
                        ?>
                    </div>
                    <div class="user-text">
                        <span class="user-name"><?= htmlspecialchars($username) ?></span>
                        <span class="user-role"><?= ucfirst($_SESSION['user']['role'] ?? 'Guest') ?></span>
                    </div>
                </div>
            </li>
            <li>
                <a href="#" class="logout-link" id="logout-btn">
                    <i class="fa fa-right-from-bracket"></i> <span>Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Logout Confirm Modal -->
<div id="logout-modal" style="
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(15,23,42,.45); backdrop-filter:blur(4px);
    align-items:center; justify-content:center;">
    <div style="
        background:#fff; border-radius:20px; padding:36px 40px; max-width:380px; width:90%;
        box-shadow:0 24px 60px rgba(15,23,42,.2); text-align:center; animation: fadeUp .2s ease;">
        <div style="
            width:60px; height:60px; border-radius:16px; margin:0 auto 20px;
            background:linear-gradient(135deg,#fee2e2,#fecaca);
            display:flex; align-items:center; justify-content:center;">
            <i class="fa fa-right-from-bracket" style="font-size:24px; color:#ef4444;"></i>
        </div>
        <h5 style="font-weight:700; color:#0f172a; margin-bottom:8px; font-size:18px;">Đăng xuất?</h5>
        <p style="color:#64748b; font-size:14px; margin-bottom:28px; line-height:1.5;">
            Bạn sẽ được chuyển về trang đăng nhập.<br>Phiên làm việc hiện tại sẽ kết thúc.
        </p>
        <div style="display:flex; gap:12px;">
            <button id="logout-cancel" style="
                flex:1; height:44px; border:1.5px solid #e2e8f0; border-radius:10px;
                background:#f8fafc; color:#475569; font-weight:600; font-size:14px;
                cursor:pointer; transition:all .2s; font-family:inherit;">
                Ở lại
            </button>
            <a href="index.php?page=logout" style="
                flex:1; height:44px; border-radius:10px; text-decoration:none;
                background:linear-gradient(135deg,#ef4444,#dc2626);
                color:#fff; font-weight:600; font-size:14px;
                display:flex; align-items:center; justify-content:center; gap:6px;
                box-shadow:0 4px 12px rgba(239,68,68,.3); transition:all .2s;">
                <i class="fa fa-right-from-bracket"></i> Đăng xuất
            </a>
        </div>
    </div>
</div>

<style>
@keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
}
.user-avatar-circle {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 700; color: #fff; letter-spacing: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── submenu toggle ──
    document.querySelectorAll('.menu-group-toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            this.parentElement.classList.toggle('open');
        });
    });

    // ── logout modal ──
    const modal      = document.getElementById('logout-modal');
    const logoutBtn  = document.getElementById('logout-btn');
    const cancelBtn  = document.getElementById('logout-cancel');

    logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        modal.style.display = 'flex';
    });

    cancelBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    modal.addEventListener('click', function (e) {
        if (e.target === modal) modal.style.display = 'none';
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') modal.style.display = 'none';
    });
});
</script>
