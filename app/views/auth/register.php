<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Capstone Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body.auth-body { background: #f1f5f9; margin: 0; padding: 0; min-height: 100vh; display: flex; align-items: stretch; }
        .auth-wrapper  { display: flex; width: 100%; min-height: 100vh; }

        .auth-panel {
            flex: 1;
            background: linear-gradient(145deg, #0f172a 0%, #1e3a5f 60%, #0f172a 100%);
            padding: 56px 64px;
            display: flex; flex-direction: column; justify-content: space-between;
            position: relative; overflow: hidden;
        }
        .auth-panel::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(56,189,248,.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(99,102,241,.10) 0%, transparent 50%);
            pointer-events: none;
        }
        .auth-panel-inner { position: relative; z-index: 1; display: flex; flex-direction: column; height: 100%; }

        .ap-brand { display: flex; align-items: center; gap: 14px; margin-bottom: 64px; }
        .ap-brand-icon {
            width: 48px; height: 48px; border-radius: 14px;
            background: linear-gradient(135deg, #38bdf8, #6366f1);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff; flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(99,102,241,.35);
        }
        .ap-brand-name { font-size: 20px; font-weight: 700; color: #f8fafc; letter-spacing: -.3px; }
        .ap-brand-sub  { font-size: 11px; color: rgba(248,250,252,.45); letter-spacing: .8px; text-transform: uppercase; }

        .ap-headline { font-size: 38px; font-weight: 800; color: #f8fafc; line-height: 1.2; letter-spacing: -1px; margin-bottom: 16px; }
        .ap-headline span { background: linear-gradient(90deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .ap-sub { font-size: 15px; color: rgba(248,250,252,.55); line-height: 1.65; max-width: 380px; margin-bottom: 48px; }

        .ap-features { display: flex; flex-direction: column; gap: 20px; margin-bottom: 48px; }
        .ap-feature {
            display: flex; align-items: center; gap: 16px;
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07);
            border-radius: 14px; padding: 16px 20px; transition: background .2s;
        }
        .ap-feature:hover { background: rgba(255,255,255,.07); }
        .ap-feat-icon {
            width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 16px;
        }
        .ap-feat-icon.blue   { background: rgba(56,189,248,.2);  color: #38bdf8; }
        .ap-feat-icon.indigo { background: rgba(99,102,241,.2);  color: #818cf8; }
        .ap-feat-icon.green  { background: rgba(16,185,129,.2);  color: #34d399; }
        .ap-feat-title { font-size: 14px; font-weight: 600; color: #f1f5f9; margin-bottom: 2px; }
        .ap-feat-desc  { font-size: 12px; color: rgba(241,245,249,.45); line-height: 1.4; }

        .ap-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .ap-stat {
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px; padding: 16px 12px; text-align: center;
        }
        .ap-stat-num   { font-size: 22px; font-weight: 800; color: #f8fafc; }
        .ap-stat-label { font-size: 10px; color: rgba(248,250,252,.4); text-transform: uppercase; letter-spacing: .8px; margin-top: 2px; }
        .ap-footer { font-size: 12px; color: rgba(248,250,252,.3); margin-top: auto; padding-top: 32px; }

        /* right side */
        .auth-main {
            flex: 1; background: #f8fafc;
            display: flex; align-items: center; justify-content: center;
            padding: 24px 32px;
        }
        .auth-card {
            width: 100%; max-width: 460px;
            background: #ffffff; border-radius: 24px;
            padding: 32px 36px; box-shadow: 0 8px 32px rgba(15,23,42,.07), 0 1px 0 rgba(255,255,255,.9) inset;
            border: 1px solid #e8edf3;
        }

        .auth-tabs {
            display: flex; background: #f1f5f9; padding: 4px; border-radius: 12px; margin-bottom: 20px;
        }
        .tab-link {
            flex: 1; text-align: center; padding: 9px 8px;
            font-size: 13px; font-weight: 600; color: #64748b;
            border-radius: 9px; transition: all .25s; text-decoration: none;
        }
        .tab-link.active { background: #ffffff; color: #0f172a; box-shadow: 0 1px 6px rgba(15,23,42,.1); }
        .tab-link:hover:not(.active) { color: #0f172a; }

        .card-heading h3 { font-size: 20px; font-weight: 700; color: #0f172a; letter-spacing: -.4px; margin-bottom: 2px; }
        .card-heading p  { font-size: 13px; color: #64748b; margin-bottom: 0; }

        .field-group { margin-bottom: 12px; }
        .field-label { font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px; display: block; }
        .input-wrap {
            display: flex; align-items: center;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            background: #f8fafc; overflow: hidden;
            transition: border-color .2s, box-shadow .2s;
        }
        .input-wrap:focus-within {
            border-color: #6366f1; background: #fff;
            box-shadow: 0 0 0 3px rgba(99,102,241,.1);
        }
        .input-wrap .ic {
            width: 40px; display: flex; align-items: center; justify-content: center;
            color: #94a3b8; font-size: 13px; flex-shrink: 0;
        }
        .input-wrap input, .input-wrap select {
            flex: 1; border: none; background: transparent;
            height: 40px; font-size: 13px; color: #0f172a;
            outline: none; padding-right: 10px;
        }
        .input-wrap input::placeholder { color: #c4cdd8; }
        .input-wrap select { appearance: auto; padding-right: 8px; }
        .input-wrap .toggle-pw {
            width: 36px; height: 40px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #94a3b8; font-size: 13px; flex-shrink: 0;
            background: transparent; border: none; transition: color .2s;
        }
        .input-wrap .toggle-pw:hover { color: #6366f1; }

        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

        .btn-auth {
            width: 100%; height: 44px; font-size: 14px; font-weight: 600;
            border-radius: 10px; border: none; color: #fff;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            box-shadow: 0 4px 14px rgba(15,23,42,.25);
            transition: all .25s; cursor: pointer; margin-top: 6px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-auth:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(15,23,42,.35); background: #1e3a5f; }

        .auth-footer-text { text-align: center; margin-top: 14px; font-size: 13px; color: #94a3b8; }
        .auth-footer-text a { color: #6366f1; font-weight: 600; text-decoration: none; }
        .auth-footer-text a:hover { text-decoration: underline; }

        /* role cards */
        .role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .role-card {
            position: relative; cursor: pointer;
            border: 1.5px solid #e2e8f0; border-radius: 12px;
            padding: 14px 12px; text-align: center;
            background: #f8fafc; transition: all .2s;
            user-select: none;
        }
        .role-card input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .role-card:hover { border-color: #a5b4fc; background: #fff; }
        .role-card.selected,
        .role-card:has(input:checked) {
            border-color: #6366f1; background: #eef2ff;
            box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        }
        .role-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; margin: 0 auto 8px;
        }
        .role-icon.blue   { background: rgba(56,189,248,.15); color: #0ea5e9; }
        .role-icon.indigo { background: rgba(99,102,241,.15); color: #6366f1; }
        .role-card.selected .role-icon.blue,
        .role-card:has(input:checked) .role-icon.blue   { background: rgba(56,189,248,.25); }
        .role-card.selected .role-icon.indigo,
        .role-card:has(input:checked) .role-icon.indigo { background: rgba(99,102,241,.25); }
        .role-name { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .role-desc { font-size: 11px; color: #94a3b8; line-height: 1.3; }

        .auth-alert {
            border-radius: 10px; padding: 11px 16px; font-size: 13px;
            display: flex; align-items: center; gap: 10px; margin-bottom: 20px; border: none;
        }
        .auth-alert.danger { background: #fef2f2; color: #991b1b; }

        @media (max-width: 991px) { .auth-panel { display: none; } .auth-main { padding: 24px 16px; } }
        @media (max-width: 480px) { .auth-card { padding: 28px 20px; border-radius: 16px; } .row-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body class="auth-body">
<div class="auth-wrapper">

    <!-- ═══ LEFT PANEL ═══ -->
    <div class="auth-panel d-none d-lg-flex">
        <div class="auth-panel-inner">

            <div class="ap-brand">
                <div class="ap-brand-icon"><i class="fa-solid fa-layer-group"></i></div>
                <div>
                    <div class="ap-brand-name">Capstone Manager</div>
                    <div class="ap-brand-sub">IS-VNU Platform</div>
                </div>
            </div>

            <div>
                <h1 class="ap-headline">Bắt đầu<br>hành trình <span>của bạn.</span></h1>
                <p class="ap-sub">Đăng ký tài khoản để tham gia mạng lưới quản lý đề án tốt nghiệp tại IS-VNU.</p>

                <div class="ap-features">
                    <div class="ap-feature">
                        <div class="ap-feat-icon blue"><i class="fa-solid fa-user-shield"></i></div>
                        <div>
                            <div class="ap-feat-title">Bảo mật thông tin</div>
                            <div class="ap-feat-desc">Hệ thống phân quyền RBAC đảm bảo an toàn dữ liệu cá nhân.</div>
                        </div>
                    </div>
                    <div class="ap-feature">
                        <div class="ap-feat-icon indigo"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                        <div>
                            <div class="ap-feat-title">Lưu trữ tập trung</div>
                            <div class="ap-feat-desc">Nộp báo cáo và tài liệu đề án trực tuyến dễ dàng.</div>
                        </div>
                    </div>
                    <div class="ap-feature">
                        <div class="ap-feat-icon green"><i class="fa-solid fa-chart-line"></i></div>
                        <div>
                            <div class="ap-feat-title">Theo dõi tiến độ</div>
                            <div class="ap-feat-desc">Quan sát tiến độ thực hiện qua các giai đoạn cụ thể.</div>
                        </div>
                    </div>
                </div>

                <div class="ap-stats">
                    <div class="ap-stat"><div class="ap-stat-num">1500+</div><div class="ap-stat-label">Sinh viên</div></div>
                    <div class="ap-stat"><div class="ap-stat-num">85+</div><div class="ap-stat-label">Giảng viên</div></div>
                    <div class="ap-stat"><div class="ap-stat-num">500+</div><div class="ap-stat-label">Đề tài</div></div>
                </div>
            </div>

            <div class="ap-footer">© 2026 Trường Quốc tế — ĐHQGHN (IS-VNU)</div>
        </div>
    </div>

    <!-- ═══ RIGHT PANEL ═══ -->
    <div class="auth-main">
        <div class="auth-card">

            <div class="auth-tabs">
                <a href="index.php?page=login"    class="tab-link">Đăng nhập</a>
                <a href="index.php?page=register" class="tab-link active">Đăng ký</a>
            </div>

            <div class="card-heading mb-3">
                <h3>Tạo tài khoản mới</h3>
                <p>Điền thông tin bên dưới để bắt đầu.</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="auth-alert danger">
                    <i class="fa-solid fa-circle-exclamation"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=register"> <?= csrfField() ?>

                <div class="field-group">
                    <label class="field-label">Họ và tên</label>
                    <div class="input-wrap">
                        <span class="ic"><i class="fa-regular fa-user"></i></span>
                        <input type="text" name="full_name"
                               placeholder="Nguyễn Văn A"
                               value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                               autocomplete="name" required>
                    </div>
                </div>

                <!-- Sinh viên: MSV + SDT | Giảng viên: chỉ SDT -->
                <div id="field-student" class="row-2 field-group" style="display:none;">
                    <div>
                        <label class="field-label">Mã sinh viên</label>
                        <div class="input-wrap">
                            <span class="ic"><i class="fa-solid fa-id-card"></i></span>
                            <input type="text" name="student_code" id="student_code"
                                   placeholder="2101xxxx"
                                   value="<?= htmlspecialchars($_POST['student_code'] ?? '') ?>">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">Số điện thoại</label>
                        <div class="input-wrap">
                            <span class="ic"><i class="fa-solid fa-phone"></i></span>
                            <input type="text" name="phone" id="phone_student"
                                   placeholder="09xxxxxxxx"
                                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div id="field-lecturer" class="field-group" style="display:none;">
                    <label class="field-label">Số điện thoại</label>
                    <div class="input-wrap">
                        <span class="ic"><i class="fa-solid fa-phone"></i></span>
                        <input type="text" name="phone" id="phone_lecturer"
                               placeholder="09xxxxxxxx"
                               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Địa chỉ Email</label>
                    <div class="input-wrap">
                        <span class="ic"><i class="fa-regular fa-envelope"></i></span>
                        <input type="email" name="email"
                               placeholder="name@vnu.edu.vn"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               autocomplete="email" required>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Mật khẩu</label>
                    <div class="input-wrap">
                        <span class="ic"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="reg-pw" name="password"
                               placeholder="Tối thiểu 6 ký tự"
                               autocomplete="new-password" required>
                        <button type="button" class="toggle-pw" onclick="togglePw('reg-pw', this)" aria-label="Hiện/ẩn mật khẩu">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Vai trò</label>
                    <div class="role-grid">
                        <label class="role-card <?= ($_POST['role'] ?? '') === 'student' ? 'selected' : '' ?>">
                            <input type="radio" name="role" value="student" <?= ($_POST['role'] ?? '') === 'student' ? 'checked' : '' ?> required>
                            <div class="role-icon blue"><i class="fa-solid fa-user-graduate"></i></div>
                            <div class="role-name">Sinh viên</div>
                            <div class="role-desc">Đăng ký &amp; theo dõi đề tài</div>
                        </label>
                        <label class="role-card <?= ($_POST['role'] ?? '') === 'lecturer' ? 'selected' : '' ?>">
                            <input type="radio" name="role" value="lecturer" <?= ($_POST['role'] ?? '') === 'lecturer' ? 'checked' : '' ?>>
                            <div class="role-icon indigo"><i class="fa-solid fa-chalkboard-user"></i></div>
                            <div class="role-name">Giảng viên</div>
                            <div class="role-desc">Hướng dẫn &amp; chấm điểm</div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-auth">
                    Tạo tài khoản <i class="fa-solid fa-user-plus"></i>
                </button>
            </form>

            <div class="auth-footer-text">
                Đã có tài khoản? <a href="index.php?page=login">Đăng nhập ngay</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-regular fa-eye';
    }
}

function updateRoleFields(role) {
    const studentFields  = document.getElementById('field-student');
    const lecturerFields = document.getElementById('field-lecturer');
    const phoneStudent   = document.getElementById('phone_student');
    const phoneLecturer  = document.getElementById('phone_lecturer');

    if (role === 'student') {
        studentFields.style.display  = '';
        lecturerFields.style.display = 'none';
        // disable input lecturer để không submit trùng name="phone"
        phoneLecturer.disabled = true;
        phoneStudent.disabled  = false;
    } else if (role === 'lecturer') {
        studentFields.style.display  = 'none';
        lecturerFields.style.display = '';
        phoneStudent.disabled  = true;
        phoneLecturer.disabled = false;
    }
}

// Role card highlight + toggle fields
document.querySelectorAll('.role-card input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
        if (radio.checked) {
            radio.closest('.role-card').classList.add('selected');
            updateRoleFields(radio.value);
        }
    });
});

// Khởi tạo khi page load (trường hợp POST lại có giá trị sẵn)
const checkedRole = document.querySelector('.role-card input[type="radio"]:checked');
if (checkedRole) {
    checkedRole.closest('.role-card').classList.add('selected');
    updateRoleFields(checkedRole.value);
}
</script>
</body>
</html>
