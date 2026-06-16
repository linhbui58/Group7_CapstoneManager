<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Capstone Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── LAYOUT ── */
        body.auth-body { background: #f1f5f9; margin: 0; padding: 0; min-height: 100vh; display: flex; align-items: stretch; }
        .auth-wrapper  { display: flex; width: 100%; min-height: 100vh; }

        /* ── LEFT PANEL ── */
        .auth-panel {
            flex: 1;
            background: linear-gradient(145deg, #0f172a 0%, #1e3a5f 60%, #0f172a 100%);
            padding: 56px 64px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .auth-panel::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(56,189,248,.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(99,102,241,.10) 0%, transparent 50%);
            pointer-events: none;
        }
        .auth-panel-inner { position: relative; z-index: 1; display: flex; flex-direction: column; height: 100%; }

        /* brand */
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

        /* headline */
        .ap-headline { font-size: 38px; font-weight: 800; color: #f8fafc; line-height: 1.2; letter-spacing: -1px; margin-bottom: 16px; }
        .ap-headline span { background: linear-gradient(90deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .ap-sub { font-size: 15px; color: rgba(248,250,252,.55); line-height: 1.65; max-width: 380px; margin-bottom: 48px; }

        /* feature cards */
        .ap-features { display: flex; flex-direction: column; gap: 20px; margin-bottom: 48px; }
        .ap-feature {
            display: flex; align-items: center; gap: 16px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 14px; padding: 16px 20px;
            transition: background .2s;
        }
        .ap-feature:hover { background: rgba(255,255,255,.07); }
        .ap-feat-icon {
            width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: #fff;
        }
        .ap-feat-icon.blue   { background: rgba(56,189,248,.2);  color: #38bdf8; }
        .ap-feat-icon.indigo { background: rgba(99,102,241,.2);  color: #818cf8; }
        .ap-feat-icon.green  { background: rgba(16,185,129,.2);  color: #34d399; }
        .ap-feat-title { font-size: 14px; font-weight: 600; color: #f1f5f9; margin-bottom: 2px; }
        .ap-feat-desc  { font-size: 12px; color: rgba(241,245,249,.45); line-height: 1.4; }

        /* stats */
        .ap-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .ap-stat {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px; padding: 16px 12px; text-align: center;
        }
        .ap-stat-num  { font-size: 22px; font-weight: 800; color: #f8fafc; }
        .ap-stat-label{ font-size: 10px; color: rgba(248,250,252,.4); text-transform: uppercase; letter-spacing: .8px; margin-top: 2px; }

        /* footer */
        .ap-footer { font-size: 12px; color: rgba(248,250,252,.3); margin-top: auto; padding-top: 32px; }

        /* ── RIGHT PANEL ── */
        .auth-main {
            flex: 1;
            background: #f8fafc;
            display: flex; align-items: center; justify-content: center;
            padding: 40px 32px;
        }
        .auth-card {
            width: 100%; max-width: 440px;
            background: #ffffff;
            border-radius: 24px;
            padding: 48px 44px;
            box-shadow: 0 8px 32px rgba(15,23,42,.07), 0 1px 0 rgba(255,255,255,.9) inset;
            border: 1px solid #e8edf3;
        }

        /* tabs */
        .auth-tabs {
            display: flex; background: #f1f5f9; padding: 4px; border-radius: 12px; margin-bottom: 36px;
        }
        .tab-link {
            flex: 1; text-align: center; padding: 10px 8px;
            font-size: 13.5px; font-weight: 600; color: #64748b;
            border-radius: 9px; transition: all .25s; text-decoration: none;
        }
        .tab-link.active {
            background: #ffffff; color: #0f172a;
            box-shadow: 0 1px 6px rgba(15,23,42,.1);
        }
        .tab-link:hover:not(.active) { color: #0f172a; }

        /* heading */
        .card-heading h3 { font-size: 24px; font-weight: 700; color: #0f172a; letter-spacing: -.4px; margin-bottom: 6px; }
        .card-heading p  { font-size: 14px; color: #64748b; }

        /* inputs */
        .field-group { margin-bottom: 20px; }
        .field-label { font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 8px; display: block; }
        .input-wrap {
            display: flex; align-items: center;
            border: 1.5px solid #e2e8f0; border-radius: 12px;
            background: #f8fafc; overflow: hidden;
            transition: border-color .2s, box-shadow .2s;
        }
        .input-wrap:focus-within {
            border-color: #6366f1; background: #fff;
            box-shadow: 0 0 0 4px rgba(99,102,241,.1);
        }
        .input-wrap .ic {
            width: 46px; display: flex; align-items: center; justify-content: center;
            color: #94a3b8; font-size: 14px; flex-shrink: 0;
        }
        .input-wrap input, .input-wrap select {
            flex: 1; border: none; background: transparent;
            height: 46px; font-size: 14px; color: #0f172a;
            outline: none; padding-right: 14px;
        }
        .input-wrap input::placeholder { color: #c4cdd8; }
        .input-wrap .toggle-pw {
            width: 40px; height: 46px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #94a3b8; font-size: 13px; flex-shrink: 0;
            transition: color .2s; background: transparent; border: none;
        }
        .input-wrap .toggle-pw:hover { color: #6366f1; }

        /* submit btn */
        .btn-auth {
            width: 100%; height: 48px; font-size: 15px; font-weight: 600;
            border-radius: 12px; border: none; color: #fff;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            box-shadow: 0 4px 14px rgba(15,23,42,.25);
            transition: all .25s; cursor: pointer; margin-top: 8px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-auth:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(15,23,42,.35); background: #1e3a5f; }
        .btn-auth:active { transform: translateY(0); }

        /* divider text */
        .auth-footer-text { text-align: center; margin-top: 24px; font-size: 13px; color: #94a3b8; }
        .auth-footer-text a { color: #6366f1; font-weight: 600; text-decoration: none; }
        .auth-footer-text a:hover { text-decoration: underline; }

        /* alerts */
        .auth-alert {
            border-radius: 10px; padding: 11px 16px; font-size: 13px;
            display: flex; align-items: center; gap: 10px; margin-bottom: 20px;
            border: none;
        }
        .auth-alert.success { background: #f0fdf4; color: #166534; }
        .auth-alert.danger  { background: #fef2f2; color: #991b1b; }

        /* responsive */
        @media (max-width: 991px) { .auth-panel { display: none; } .auth-main { padding: 24px 16px; } }
        @media (max-width: 480px) { .auth-card { padding: 32px 24px; border-radius: 16px; } }
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
                <h1 class="ap-headline">Quản lý đề án<br><span>thông minh hơn.</span></h1>
                <p class="ap-sub">Hệ thống hỗ trợ sinh viên IS-VNU tối ưu hóa quy trình thực hiện Capstone Project từ đăng ký đến nghiệm thu.</p>

                <div class="ap-features">
                    <div class="ap-feature">
                        <div class="ap-feat-icon blue"><i class="fa-solid fa-magnifying-glass"></i></div>
                        <div>
                            <div class="ap-feat-title">Đề xuất đề tài thông minh</div>
                            <div class="ap-feat-desc">Hệ thống matching giảng viên dựa trên chuyên môn và sở thích.</div>
                        </div>
                    </div>
                    <div class="ap-feature">
                        <div class="ap-feat-icon indigo"><i class="fa-solid fa-list-check"></i></div>
                        <div>
                            <div class="ap-feat-title">Quản lý Milestones</div>
                            <div class="ap-feat-desc">Nộp báo cáo giai đoạn và theo dõi tiến độ thực tế.</div>
                        </div>
                    </div>
                    <div class="ap-feature">
                        <div class="ap-feat-icon green"><i class="fa-solid fa-star"></i></div>
                        <div>
                            <div class="ap-feat-title">Đánh giá &amp; Kết quả</div>
                            <div class="ap-feat-desc">Nhận phản hồi trực tiếp và điểm số từ hội đồng.</div>
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
                <a href="index.php?page=login"    class="tab-link active">Đăng nhập</a>
                <a href="index.php?page=register" class="tab-link">Đăng ký</a>
            </div>

            <div class="card-heading mb-4">
                <h3>Chào mừng trở lại!</h3>
                <p>Vui lòng đăng nhập để tiếp tục quản lý dự án.</p>
            </div>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="auth-alert success">
                    <i class="fa-solid fa-circle-check"></i><?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if(isset($error)): ?>
                <div class="auth-alert danger">
                    <i class="fa-solid fa-circle-exclamation"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=login"> <?= csrfField() ?>

                <div class="field-group">
                    <label class="field-label" for="login-email">Địa chỉ Email</label>
                    <div class="input-wrap">
                        <span class="ic"><i class="fa-regular fa-envelope"></i></span>
                        <input type="email" id="login-email" name="email"
                               placeholder="name@vnu.edu.vn"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               autocomplete="email" required>
                    </div>
                </div>

                <div class="field-group">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="field-label mb-0" for="login-pw">Mật khẩu</label>
                        <a href="index.php?page=forgot-password" style="font-size:12px;color:#6366f1;text-decoration:none;font-weight:600;">Quên mật khẩu?</a>
                    </div>
                    <div class="input-wrap">
                        <span class="ic"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="login-pw" name="password"
                               placeholder="••••••••"
                               autocomplete="current-password" required>
                        <button type="button" class="toggle-pw" onclick="togglePw('login-pw', this)" aria-label="Hiện/ẩn mật khẩu">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-auth">
                    Đăng nhập hệ thống <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div class="auth-footer-text">
                Bạn mới sử dụng hệ thống? <a href="index.php?page=register">Đăng ký ngay</a>
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
</script>
</body>
</html>
