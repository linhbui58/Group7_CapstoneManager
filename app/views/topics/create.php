<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<?php $role = $_SESSION['user']['role']; ?>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=topics" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Trá»Ÿ vá» danh sÃ¡ch
        </a>
        <h2 class="fw-bold mt-2">
            <?= $role === 'admin' ? 'ThÃªm Äá» TÃ i Má»›i' : 'Táº¡o & ÄÄƒng KÃ½ Äá» TÃ i' ?>
        </h2>
        <?php if ($role === 'student'): ?>
            <p class="text-muted small">MÃ´ táº£ Ä‘á» tÃ i báº¡n muá»‘n thá»±c hiá»‡n vÃ  chá»n giáº£ng viÃªn hÆ°á»›ng dáº«n mong muá»‘n.</p>
        <?php endif; ?>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 800px;">
        <form action="index.php?page=topic-store" method="POST"> <?= csrfField() ?>

            <!-- TiÃªu Ä‘á» -->
            <div class="mb-3">
                <label class="fw-bold small text-muted">TIÃŠU Äá»€ Äá»€ TÃ€I <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control rounded-pill px-3"
                       placeholder="Nháº­p tÃªn Ä‘á» tÃ i..." required>
            </div>

            <div class="row mb-3">
                <!-- Há»c ká»³ -->
                <div class="col-md-<?= $role === 'admin' ? '6' : '12' ?>">
                    <label class="fw-bold small text-muted">Há»ŒC Ká»² <span class="text-danger">*</span></label>
                    <select name="semester_id" class="form-select rounded-pill px-3" required>
                        <option value="">-- Chá»n há»c ká»³ --</option>
                        <?php foreach ($semesters as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if ($role === 'admin'): ?>
                <!-- Tráº¡ng thÃ¡i (admin only) -->
                <div class="col-md-6">
                    <label class="fw-bold small text-muted">TRáº NG THÃI</label>
                    <select name="status" class="form-select rounded-pill px-3">
                        <option value="pending">Chá» duyá»‡t</option>
                        <option value="approved">ÄÃ£ duyá»‡t</option>
                    </select>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($role === 'student'): ?>
            <!-- Giáº£ng viÃªn mong muá»‘n (student only) -->
            <div class="mb-3">
                <label class="fw-bold small text-muted">GIáº¢NG VIÃŠN HÆ¯á»šNG DáºªN MONG MUá»N</label>
                <select name="desired_lecturer_id" class="form-select rounded-pill px-3">
                    <option value="">-- KhÃ´ng chá»‰ Ä‘á»‹nh --</option>
                    <?php foreach ($lecturers as $l): ?>
                        <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <!-- Keywords -->
            <div class="mb-3">
                <label class="fw-bold small text-muted">Tá»ª KHÃ“A</label>
                <input type="text" name="keywords" class="form-control rounded-pill px-3"
                       placeholder="AI, Machine Learning, Web...">
            </div>

            <!-- MÃ´ táº£ -->
            <div class="mb-4">
                <label class="fw-bold small text-muted">MÃ” Táº¢</label>
                <textarea name="description" class="form-control" rows="4"
                          style="border-radius: 15px;"
                          placeholder="MÃ´ táº£ chi tiáº¿t vá» Ä‘á» tÃ i..."></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                    <?= $role === 'admin' ? 'LÆ°u Äá» TÃ i' : 'Gá»­i ÄÄƒng KÃ½' ?>
                </button>
                <a href="index.php?page=topics" class="btn btn-light rounded-pill px-4">Há»§y</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
