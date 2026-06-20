<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Capstone Manager</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ── RESET & BASE ── */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #0f172a; overflow: hidden; height: 100vh; }
        
        /* ── BACKGROUND ── */
        .auth-page {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            display: flex;
        }
        
        .auth-background {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            background: url('assets/images/bg-lab.png') center/cover no-repeat;
            z-index: 1;
        }
        .auth-background::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(90deg, rgba(2,6,23,0.92) 0%, rgba(2,6,23,0.85) 35%, rgba(2,6,23,0.4) 65%, transparent 100%);
            z-index: 2; pointer-events: none;
        }

        /* ── GRID LAYOUT ── */
        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            height: 100vh;
            display: grid;
            grid-template-columns: 58% 42%;
        }

        .auth-left {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100vh;
            padding-left: 60px;
            padding-bottom: 45px;
        }

        .auth-right {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        /* ── HERO CONTENT ── */
        .hero-content {
            position: absolute;
            left: 60px;
            top: 20px;
            width: 750px;
            z-index: 5;
            display: flex; flex-direction: column;
        }

        @media (max-height: 700px) {
            .hero-content { transform: scale(0.85); transform-origin: top left; }
        }
        @media (max-height: 600px) {
            .hero-content { transform: scale(0.75); transform-origin: top left; }
        }

        .ap-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
        .ap-brand img { height: 50px; object-fit: contain; display: block; }
        .ap-brand-text { display: flex; flex-direction: column; }
        .ap-brand-text h1 { font-size: 28px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; line-height: 1.2; }
        .ap-brand-text span { font-size: 13px; font-weight: 600; color: #38bdf8; text-transform: uppercase; letter-spacing: 1.5px; }

        .hero-title { font-size: 46px; font-weight: 800; color: #ffffff; line-height: 1.15; letter-spacing: -1px; margin-bottom: 24px; white-space: nowrap; }
        .hero-title .highlight { background: linear-gradient(135deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-desc { font-size: 18px; color: rgba(255, 255, 255, 0.7); line-height: 1.6; max-width: 620px; margin-bottom: 32px; }

        /* ── FEATURES ── */
        .ap-features { display: flex; flex-direction: column; gap: 16px; max-width: 620px; margin-bottom: 30px; }
        .ap-feature-card { display: flex; align-items: flex-start; gap: 18px; padding: 20px 24px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 16px; transition: all 0.3s ease; animation: fadeIn 0.8s ease-out forwards; opacity: 0; }
        .ap-feature-card:nth-child(1) { animation-delay: 0.1s; }
        .ap-feature-card:nth-child(2) { animation-delay: 0.15s; }
        .ap-feature-card:nth-child(3) { animation-delay: 0.2s; }
        .ap-feature-card:nth-child(4) { animation-delay: 0.25s; }
        .ap-feature-card:hover { background: rgba(255, 255, 255, 0.08); transform: translateX(5px); border-color: rgba(255, 255, 255, 0.1); }
        .ap-feature-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .ap-feature-card:nth-child(1) .ap-feature-icon { background: rgba(56, 189, 248, 0.15); color: #38bdf8; }
        .ap-feature-card:nth-child(2) .ap-feature-icon { background: rgba(129, 140, 248, 0.15); color: #818cf8; }
        .ap-feature-card:nth-child(3) .ap-feature-icon { background: rgba(52, 211, 153, 0.15); color: #34d399; }
        .ap-feature-card:nth-child(4) .ap-feature-icon { background: rgba(250, 204, 21, 0.15); color: #facc15; }
        .ap-feature-text h4 { font-size: 17px; font-weight: 700; color: #ffffff; margin-bottom: 4px; }
        .ap-feature-text p { font-size: 14px; color: rgba(255, 255, 255, 0.5); line-height: 1.5; }

        /* ── STATS SECTION ── */
        .stats-section {
            width: 100%; max-width: 620px;
            margin-top: -10px;
            position: relative;
            z-index: 5;
            display: flex; align-items: center; justify-content: space-between; padding: 24px 32px; background: rgba(0, 0, 0, 0.35); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; animation: fadeIn 0.8s ease-out 0.3s forwards; opacity: 0;
        }
        .ap-stat { display: flex; flex-direction: column; align-items: center; gap: 8px; position: relative; flex: 1; }
        .ap-stat:not(:last-child)::after { content: ''; position: absolute; right: 0; top: 10%; height: 80%; width: 1px; background: rgba(255, 255, 255, 0.15); }
        .ap-stat-num { font-size: 32px; font-weight: 800; color: #ffffff; line-height: 1; }
        .ap-stat-label { font-size: 13px; font-weight: 600; color: rgba(255, 255, 255, 0.65); letter-spacing: 1px; text-transform: uppercase; }

        /* ── REGISTER CARD ── */
        .auth-card {
            position: absolute;
            right: 80px;
            top: calc(50% - 22.5px);
            transform: translateY(-50%);
            width: 100%;
            max-width: 520px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 30px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
            z-index: 10;
            max-height: 85vh;
            overflow-y: auto;
        }
        
        .auth-card::-webkit-scrollbar { width: 6px; }
        .auth-card::-webkit-scrollbar-track { background: transparent; }
        .auth-card::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        .auth-tabs { display: flex; background: #f1f5f9; padding: 6px; border-radius: 16px; margin-bottom: 24px; }
        .tab-link { flex: 1; text-align: center; padding: 10px; font-size: 14px; font-weight: 600; color: #64748b; text-decoration: none; border-radius: 12px; transition: all 0.3s ease; }
        .tab-link:hover { color: #0f172a; }
        .tab-link.active { background: linear-gradient(135deg, #0ea5e9, #4f46e5); color: #ffffff; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3); }

        .card-heading { margin-bottom: 24px; }
        .card-heading h3 { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 6px; letter-spacing: -0.5px; }
        .card-heading p { font-size: 13px; color: #64748b; }

        .role-selector { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .role-card { position: relative; cursor: pointer; }
        .role-card input { position: absolute; opacity: 0; }
        .role-card-inner { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; padding: 12px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.2s ease; }
        .role-card-inner i { font-size: 20px; color: #64748b; transition: color 0.2s; }
        .role-card-inner span { font-size: 13px; font-weight: 600; color: #475569; }
        .role-card input:checked + .role-card-inner { background: #f0f9ff; border-color: #0ea5e9; box-shadow: 0 4px 15px rgba(14, 165, 233, 0.15); }
        .role-card input:checked + .role-card-inner i { color: #0ea5e9; }
        .role-card input:checked + .role-card-inner span { color: #0284c7; }

        .field-group { margin-bottom: 16px; }
        .field-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        .field-header label { font-size: 13px; font-weight: 600; color: #334155; }
        
        .input-wrapper { position: relative; }
        .input-wrapper i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 14px; pointer-events: none; transition: color 0.3s; }
        .input-wrapper input, .input-wrapper select { width: 100%; padding: 12px 14px 12px 40px; background: #ffffff; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 13px; color: #0f172a; font-weight: 500; transition: all 0.3s ease; }
        .input-wrapper input:focus, .input-wrapper select:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1); }
        .input-wrapper input:focus + i, .input-wrapper select:focus + i { color: #0ea5e9; }
        .input-wrapper select { appearance: none; padding-right: 36px; cursor: pointer; }
        .select-arrow { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #64748b; font-size: 12px; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .btn-submit { width: 100%; padding: 14px; background: linear-gradient(135deg, #0ea5e9, #4f46e5); color: #ffffff; border: none; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 6px; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -8px rgba(79, 70, 229, 0.6); opacity: 0.95; }

        .auth-footer { margin-top: 20px; text-align: center; font-size: 13px; color: #64748b; }
        .auth-footer a { color: #0ea5e9; font-weight: 600; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }

        .auth-alert { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 500; margin-bottom: 20px; }
        .auth-alert.danger { background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; }

        /* ── BOTTOM WAVE ── */
        .auth-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 45px;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }
        .auth-wave svg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: block;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<div class="auth-page">
    <div class="auth-background"></div>

    <div class="auth-container">
        <!-- ═══ LEFT PANEL ═══ -->
        <div class="auth-left">
            <div class="hero-content">
                <!-- BRAND -->
                <div class="ap-brand">
                    <img src="assets/images/logo-vnuis.png" alt="VNU-IS Logo">
                    <div class="ap-brand-text">
                        <h1>INTERNATIONAL SCHOOL</h1>
                        <span>VIETNAM NATIONAL UNIVERSITY, HA NOI</span>
                    </div>
                </div>

                <!-- TEXT -->
                <h2 class="hero-title">NỀN TẢNG<br><span class="highlight">QUẢN LÝ ĐỒ ÁN TỐT NGHIỆP</span></h2>
                <p class="hero-desc">Hệ thống hỗ trợ sinh viên IS-VNU tối ưu hóa quy trình thực hiện Capstone Project từ đăng ký đến nghiệm thu.</p>

                <!-- FEATURE CARDS -->
                <div class="ap-features">
                    <div class="ap-feature-card">
                        <div class="ap-feature-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                        <div class="ap-feature-text">
                            <h4>Đề xuất đề tài thông minh</h4>
                            <p>Hệ thống matching giảng viên dựa trên chuyên môn và sở thích.</p>
                        </div>
                    </div>
                    <div class="ap-feature-card">
                        <div class="ap-feature-icon"><i class="fa-solid fa-list-check"></i></div>
                        <div class="ap-feature-text">
                            <h4>Quản lý Milestones</h4>
                            <p>Nộp báo cáo giai đoạn và theo dõi tiến độ thực tế.</p>
                        </div>
                    </div>
                    <div class="ap-feature-card">
                        <div class="ap-feature-icon"><i class="fa-solid fa-star"></i></div>
                        <div class="ap-feature-text">
                            <h4>Đánh giá & Kết quả</h4>
                            <p>Nhận phản hồi trực tiếp và điểm số từ hội đồng.</p>
                        </div>
                    </div>
                </div>

                <!-- STATS SECTION -->
                <div class="stats-section">
                    <div class="ap-stat">
                        <div class="ap-stat-num">1500+</div>
                        <div class="ap-stat-label">SINH VIÊN</div>
                    </div>
                    <div class="ap-stat">
                        <div class="ap-stat-num">85+</div>
                        <div class="ap-stat-label">GIẢNG VIÊN</div>
                    </div>
                    <div class="ap-stat">
                        <div class="ap-stat-num">500+</div>
                        <div class="ap-stat-label">ĐỀ TÀI</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ RIGHT PANEL (REGISTER CARD) ═══ -->
        <div class="auth-right">
            <div class="auth-card">
                <div class="auth-tabs">
                    <a href="index.php?page=login"    class="tab-link">Đăng nhập</a>
                    <a href="index.php?page=register" class="tab-link active">Đăng ký</a>
                </div>

                <div class="card-heading mb-4">
                    <h3>Tạo tài khoản</h3>
                    <p>Chào mừng bạn! Vui lòng điền thông tin bên dưới.</p>
                </div>

                <?php $old = $old ?? []; ?>
                <?php if(isset($error)): ?>
                    <div class="auth-alert danger">
                        <i class="fa-solid fa-circle-exclamation"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=register"> <?= csrfField() ?>
                    
                    <div class="field-group role-selector">
                        <label class="role-card">
                            <input type="radio" name="role" value="student" <?= (isset($old['role']) && $old['role'] === 'student') ? 'checked' : '' ?> required>
                            <div class="role-card-inner">
                                <i class="fa-solid fa-user-graduate"></i>
                                <span>Sinh viên</span>
                            </div>
                        </label>
                        <label class="role-card">
                            <input type="radio" name="role" value="lecturer" <?= (isset($old['role']) && $old['role'] === 'lecturer') ? 'checked' : '' ?> required>
                            <div class="role-card-inner">
                                <i class="fa-solid fa-chalkboard-user"></i>
                                <span>Giảng viên</span>
                            </div>
                        </label>
                    </div>

                    <div class="field-group">
                        <div class="field-header"><label for="reg-name">Họ và tên</label></div>
                        <div class="input-wrapper">
                            <input type="text" id="reg-name" name="name"
                                   placeholder="VD: Nguyễn Văn A"
                                   value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                            <i class="fa-regular fa-user"></i>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field-header"><label for="reg-email">Email</label></div>
                        <div class="input-wrapper">
                            <input type="email" id="reg-email" name="email"
                                   placeholder="name@example.com"
                                   value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                    </div>

                    <!-- Mã sinh viên: chỉ hiện với sinh viên -->
                    <div class="field-group" id="student-code-field">
                        <div class="field-header"><label for="student_code">Mã sinh viên</label></div>
                        <div class="input-wrapper">
                            <input type="text" id="student_code" name="student_code"
                                   placeholder="VD: 2005XXXX"
                                   value="<?= htmlspecialchars($old['student_code'] ?? '') ?>">
                            <i class="fa-solid fa-id-badge"></i>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="field-group">
                            <div class="field-header"><label for="phone">Điện thoại</label></div>
                            <div class="input-wrapper">
                                <input type="text" id="phone" name="phone"
                                       placeholder="09xx..."
                                       value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                        </div>
                        <div class="field-group">
                            <div class="field-header"><label for="department">Khoa/Viện</label></div>
                            <div class="input-wrapper">
                                <select id="department" name="department" required>
                                    <option value="">Chọn Khoa</option>
                                    <option value="Khoa CNTT" <?= (isset($old['department']) && $old['department'] === 'Khoa CNTT') ? 'selected' : '' ?>>Khoa CNTT</option>
                                    <option value="Khoa Kinh tế" <?= (isset($old['department']) && $old['department'] === 'Khoa Kinh tế') ? 'selected' : '' ?>>Khoa Kinh tế</option>
                                    <option value="Khoa Ngôn ngữ" <?= (isset($old['department']) && $old['department'] === 'Khoa Ngôn ngữ') ? 'selected' : '' ?>>Khoa Ngôn ngữ</option>
                                </select>
                                <i class="fa-solid fa-building"></i>
                                <i class="fa-solid fa-chevron-down select-arrow"></i>
                            </div>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field-header"><label for="reg-password">Mật khẩu</label></div>
                        <div class="input-wrapper">
                            <input type="password" id="reg-password" name="password" placeholder="••••••••" required>
                            <i class="fa-solid fa-lock"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Tạo tài khoản <i class="fa-solid fa-user-plus"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    Đã có tài khoản? <a href="index.php?page=login">Đăng nhập</a>
                </div>
            </div>
        </div>
    </div>


</div>

</body>
</html>

<script>
    (function () {
        const radios = document.querySelectorAll('input[name="role"]');
        const studentCodeField = document.getElementById('student-code-field');
        const studentCodeInput = document.getElementById('student_code');

        function toggleFields() {
            const selected = document.querySelector('input[name="role"]:checked');
            if (!selected) return;

            if (selected.value === 'student') {
                studentCodeField.style.display = 'block';
                studentCodeInput.setAttribute('required', 'required');
            } else {
                studentCodeField.style.display = 'none';
                studentCodeInput.removeAttribute('required');
                studentCodeInput.value = '';
            }
        }

        radios.forEach(r => r.addEventListener('change', toggleFields));
        toggleFields();
    })();
</script>
