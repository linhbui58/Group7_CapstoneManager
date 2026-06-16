<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Capstone Manager</title>
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
            
            <h1 class="display-5 fw-bold mb-4">Báº¯t Ä‘áº§u hÃ nh trÃ¬nh <br><span class="text-info">sÃ¡ng táº¡o cá»§a báº¡n.</span></h1>
            <p class="lead opacity-75 mb-5">ÄÄƒng kÃ½ tÃ i khoáº£n Ä‘á»ƒ tham gia vÃ o máº¡ng lÆ°á»›i quáº£n lÃ½ Ä‘á»“ Ã¡n tá»‘t nghiá»‡p táº¡i IS-VNU.</p>

            <div class="workflow-steps mb-5">
                <div class="step-item mb-4">
                    <div class="step-icon"><i class="fa-solid fa-user-shield"></i></div>
                    <div class="step-info">
                        <h6>Báº£o máº­t thÃ´ng tin</h6>
                        <p class="small opacity-75">Há»‡ thá»‘ng phÃ¢n quyá»n RBAC Ä‘áº£m báº£o an toÃ n dá»¯ liá»‡u.</p>
                    </div>
                </div>
                <div class="step-item mb-4">
                    <div class="step-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                    <div class="step-info">
                        <h6>LÆ°u trá»¯ táº­p trung</h6>
                        <p class="small opacity-75">Ná»™p bÃ¡o cÃ¡o vÃ  tÃ i liá»‡u Ä‘á»“ Ã¡n trá»±c tuyáº¿n dá»… dÃ ng.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="step-info">
                        <h6>Theo dÃµi biá»ƒu Ä‘á»“</h6>
                        <p class="small opacity-75">Quan sÃ¡t tiáº¿n Ä‘á»™ thá»±c hiá»‡n qua cÃ¡c giai Ä‘oáº¡n cá»¥ thá»ƒ.</p>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-box">
                    <h4 class="fw-bold">1500+</h4>
                    <p class="small m-0">Sinh viÃªn</p>
                </div>
                <div class="stat-box">
                    <h4 class="fw-bold">85+</h4>
                    <p class="small m-0">Giáº£ng viÃªn</p>
                </div>
                <div class="stat-box">
                    <h4 class="fw-bold">500+</h4>
                    <p class="small m-0">Äá» tÃ i</p>
                </div>
            </div>

            <div class="sidebar-footer mt-auto pt-5">
                <p class="small opacity-50">Â© 2026 TrÆ°á»ng Quá»‘c táº¿ - ÄHQGHN (IS-VNU)</p>
            </div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-card-new">
            <div class="auth-tabs mb-4">
                <a href="index.php?page=login" class="tab-item">ÄÄƒng nháº­p</a>
                <a href="index.php?page=register" class="tab-item active">ÄÄƒng kÃ½</a>
            </div>

            <div class="auth-header-text mb-4">
                <h3 class="fw-bold">Táº¡o tÃ i khoáº£n má»›i</h3>
                <p class="text-muted small">Äiá»n thÃ´ng tin bÃªn dÆ°á»›i Ä‘á»ƒ Ä‘Äƒng kÃ½ há»‡ thá»‘ng.</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger border-0 shadow-sm py-2 small">
                    <i class="fa-solid fa-circle-exclamation me-2"></i><?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=register"> <?= csrfField() ?>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold">Há» vÃ  tÃªn</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-muted"></i></span>
                        <input type="text" name="full_name" class="form-control bg-light border-start-0 shadow-none" placeholder="Nháº­p há» vÃ  tÃªn" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold">MÃ£ sinh viÃªn</label>
                        <input type="text" name="student_code" class="form-control bg-light shadow-none" placeholder="2101xxxx">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold">Sá»‘ Ä‘iá»‡n thoáº¡i</label>
                        <input type="text" name="phone" class="form-control bg-light shadow-none" placeholder="09xx...">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Äá»‹a chá»‰ Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control bg-light border-start-0 shadow-none" placeholder="name@vnu.edu.vn" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Máº­t kháº©u</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-muted"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-start-0 shadow-none" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Vai trÃ²</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-users-gear text-muted"></i></span>
                        <select name="role" class="form-select bg-light border-start-0 shadow-none" required>
                            <option value="" disabled selected>â€” Chá»n vai trÃ² â€”</option>
                            <option value="student">Sinh viÃªn</option>
                            <option value="lecturer">Giáº£ng viÃªn</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-3 shadow-sm">
                    ÄÄƒng kÃ½ tÃ i khoáº£n <i class="fa-solid fa-user-plus ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted">ÄÃ£ cÃ³ tÃ i khoáº£n? <a href="index.php?page=login" class="fw-bold text-decoration-none">ÄÄƒng nháº­p ngay</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
