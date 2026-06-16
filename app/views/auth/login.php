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
            
            <h1 class="display-5 fw-bold mb-4">Quáº£n lÃ½ Ä‘á»“ Ã¡n <br><span class="text-info">thÃ´ng minh hÆ¡n.</span></h1>
            <p class="lead opacity-75 mb-5">Há»‡ thá»‘ng há»— trá»£ sinh viÃªn IS-VNU tá»‘i Æ°u hÃ³a quy trÃ¬nh thá»±c hiá»‡n Capstone Project.</p>

            <div class="workflow-steps mb-5">
                <div class="step-item mb-4">
                    <div class="step-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <div class="step-info">
                        <h6>Äá» xuáº¥t Äá» tÃ i</h6>
                        <p class="small opacity-75">Há»‡ thá»‘ng matching giáº£ng viÃªn dá»±a trÃªn chuyÃªn mÃ´n.</p>
                    </div>
                </div>
                <div class="step-item mb-4">
                    <div class="step-icon"><i class="fa-solid fa-list-check"></i></div>
                    <div class="step-info">
                        <h6>Quáº£n lÃ½ Milestones</h6>
                        <p class="small opacity-75">Ná»™p bÃ¡o cÃ¡o giai Ä‘oáº¡n vÃ  theo dÃµi tiáº¿n Ä‘á»™ thá»±c táº¿.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-icon"><i class="fa-solid fa-star"></i></div>
                    <div class="step-info">
                        <h6>ÄÃ¡nh giÃ¡ & Káº¿t quáº£</h6>
                        <p class="small opacity-75">Nháº­n pháº£n há»“i trá»±c tiáº¿p vÃ  Ä‘iá»ƒm sá»‘ tá»« há»™i Ä‘á»“ng.</p>
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
                <a href="index.php?page=login" class="tab-item active">ÄÄƒng nháº­p</a>
                <a href="index.php?page=register" class="tab-item">ÄÄƒng kÃ½</a>
            </div>

            <div class="auth-header-text mb-4">
                <h3 class="fw-bold">ChÃ o má»«ng trá»Ÿ láº¡i!</h3>
                <p class="text-muted small">Vui lÃ²ng Ä‘Äƒng nháº­p Ä‘á»ƒ tiáº¿p tá»¥c quáº£n lÃ½ dá»± Ã¡n.</p>
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

            <form method="POST" action="index.php?page=login"> <?= csrfField() ?>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Äá»‹a chá»‰ Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control bg-light border-start-0 shadow-none" placeholder="name@vnu.edu.vn" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label small fw-bold">Máº­t kháº©u</label>
                        <a href="#" class="text-decoration-none small fw-bold" style="font-size: 11px;">QuÃªn máº­t kháº©u?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-start-0 shadow-none" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-3 shadow-sm">
                    ÄÄƒng nháº­p há»‡ thá»‘ng <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-5">
                <p class="small text-muted">Báº¡n má»›i sá»­ dá»¥ng há»‡ thá»‘ng? <a href="index.php?page=register" class="fw-bold text-decoration-none">ÄÄƒng kÃ½ ngay</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
