<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Capstone Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-body">

<div class="auth-wrapper">
    <div class="auth-sidebar d-none d-lg-flex">
        <div class="sidebar-content">
            <div class="brand-logo mb-5">
                <i class="fa-solid fa-layer-group fa-3x"></i>
                <span class="ms-3 fs-2 fw-bold">Capstone</span>
            </div>
            
            <h1 class="display-5 fw-bold mb-4">Quản lý đồ án <br><span class="text-info">thông minh hơn.</span></h1>
            <p class="lead opacity-75 mb-5">Hệ thống hỗ trợ sinh viên IS-VNU tối ưu hóa quy trình thực hiện Capstone Project.</p>

            <div class="workflow-steps mb-5">
                <div class="step-item mb-4">
                    <div class="step-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <div class="step-info">
                        <h6>Đề xuất Đề tài</h6>
                        <p class="small opacity-75">Hệ thống matching giảng viên dựa trên chuyên môn.</p>
                    </div>
                </div>
                <div class="step-item mb-4">
                    <div class="step-icon"><i class="fa-solid fa-list-check"></i></div>
                    <div class="step-info">
                        <h6>Quản lý Milestones</h6>
                        <p class="small opacity-75">Nộp báo cáo giai đoạn và theo dõi tiến độ thực tế.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-icon"><i class="fa-solid fa-star"></i></div>
                    <div class="step-info">
                        <h6>Đánh giá & Kết quả</h6>
                        <p class="small opacity-75">Nhận phản hồi trực tiếp và điểm số từ hội đồng.</p>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-box">
                    <h4 class="fw-bold">1500+</h4>
                    <p class="small m-0">Sinh viên</p>
                </div>
                <div class="stat-box">
                    <h4 class="fw-bold">85+</h4>
                    <p class="small m-0">Giảng viên</p>
                </div>
                <div class="stat-box">
                    <h4 class="fw-bold">500+</h4>
                    <p class="small m-0">Đề tài</p>
                </div>
            </div>

            <div class="sidebar-footer mt-auto pt-5">
                <p class="small opacity-50">© 2026 Trường Quốc tế - ĐHQGHN (IS-VNU)</p>
            </div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-card-new">
            <div class="auth-tabs mb-4">
                <a href="index.php?page=login" class="tab-item active">Đăng nhập</a>
                <a href="index.php?page=register" class="tab-item">Đăng ký</a>
            </div>

            <div class="auth-header-text mb-4">
                <h3 class="fw-bold">Chào mừng trở lại!</h3>
                <p class="text-muted small">Vui lòng đăng nhập để tiếp tục quản lý dự án.</p>
            </div>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success border-0 shadow-sm py-2 small">
                    <i class="fa-solid fa-check-circle me-2"></i><?= $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger border-0 shadow-sm py-2 small">
                    <i class="fa-solid fa-circle-exclamation me-2"></i><?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=login">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Địa chỉ Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control bg-light border-start-0 shadow-none" placeholder="name@vnu.edu.vn" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label small fw-bold">Mật khẩu</label>
                        <a href="#" class="text-decoration-none small fw-bold" style="font-size: 11px;">Quên mật khẩu?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-start-0 shadow-none" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-3 shadow-sm">
                    Đăng nhập hệ thống <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-5">
                <p class="small text-muted">Bạn mới sử dụng hệ thống? <a href="index.php?page=register" class="fw-bold text-decoration-none">Đăng ký ngay</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
