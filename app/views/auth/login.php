<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Capstone Manager</title>
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

        /* ── LOGIN CARD ── */
        .auth-card {
            position: absolute;
            right: 80px;
            top: calc(50% - 22.5px);
            transform: translateY(-50%);
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
            z-index: 10;
        }

        .auth-tabs { display: flex; background: #f1f5f9; padding: 6px; border-radius: 16px; margin-bottom: 32px; }
        .tab-link { flex: 1; text-align: center; padding: 12px; font-size: 14px; font-weight: 600; color: #64748b; text-decoration: none; border-radius: 12px; transition: all 0.3s ease; }
        .tab-link:hover { color: #0f172a; }
        .tab-link.active { background: linear-gradient(135deg, #0ea5e9, #4f46e5); color: #ffffff; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3); }

        .card-heading { margin-bottom: 24px; }
        .card-heading h3 { font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 6px; letter-spacing: -0.5px; }
        .card-heading p { font-size: 14px; color: #64748b; }

        .field-group { margin-bottom: 20px; }
        .field-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .field-header label { font-size: 13px; font-weight: 600; color: #334155; }
        .forgot-link { font-size: 13px; font-weight: 600; color: #0ea5e9; text-decoration: none; transition: color 0.2s; }
        .forgot-link:hover { color: #0284c7; }

        .input-wrapper { position: relative; }
        .input-wrapper i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 16px; pointer-events: none; transition: color 0.3s; }
        .input-wrapper input { width: 100%; padding: 14px 16px 14px 44px; background: #ffffff; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; color: #0f172a; font-weight: 500; transition: all 0.3s ease; }
        .input-wrapper input:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1); }
        .input-wrapper input:focus + i { color: #0ea5e9; }

        .btn-submit { width: 100%; padding: 14px; background: linear-gradient(135deg, #0ea5e9, #4f46e5); color: #ffffff; border: none; border-radius: 12px; font-size: 15px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 10px; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -8px rgba(79, 70, 229, 0.6); opacity: 0.95; }

        .auth-footer { margin-top: 24px; text-align: center; font-size: 14px; color: #64748b; }
        .auth-footer a { color: #0ea5e9; font-weight: 600; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }

        .auth-alert { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 500; margin-bottom: 24px; }
        .auth-alert.danger { background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; }
        .auth-alert.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #22c55e; }

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

        <!-- ═══ RIGHT PANEL (LOGIN CARD) ═══ -->
        <div class="auth-right">
            <div class="auth-card">
                <div class="auth-tabs">
                    <a href="index.php?page=login" class="tab-link active">Đăng nhập</a>
                    <a href="index.php?page=register" class="tab-link">Đăng ký</a>
                </div>

                <div class="card-heading mb-4">
                    <h3>Chào mừng trở lại!</h3>
                    <p>Vui lòng đăng nhập để tiếp tục quản lý dự án.</p>
                </div>

                <?php if(isset($error)): ?>
                    <div class="auth-alert danger">
                        <i class="fa-solid fa-circle-exclamation"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <?php if(isset($_GET['registered'])): ?>
                    <div class="auth-alert success">
                        <i class="fa-solid fa-circle-check"></i>Đăng ký thành công! Vui lòng đăng nhập.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=login"> <?= csrfField() ?>
                    <div class="field-group">
                        <div class="field-header">
                            <label for="email">Địa chỉ Email</label>
                        </div>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" 
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                                   placeholder="name@vnu.edu.vn" required autofocus>
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field-header">
                            <label for="password">Mật khẩu</label>
                            <a href="#" class="forgot-link">Quên mật khẩu?</a>
                        </div>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <i class="fa-solid fa-lock"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Đăng nhập hệ thống <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    Bạn mới sử dụng hệ thống? <a href="index.php?page=register">Đăng ký ngay</a>
                </div>
            </div>
        </div>
    </div>


</div>

</body>
</html>
