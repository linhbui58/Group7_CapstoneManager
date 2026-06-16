<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
    .glass-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        padding: 35px;
    }
    .form-label-custom { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px; display: block; }
    .input-elegant { height: 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; padding: 0 20px; width: 100%; }
</style>

<div class="main-content" style="padding: 40px; background-color: #f8fafc; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=scores" class="text-decoration-none text-muted small fw-bold"><i class="fa-solid fa-arrow-left"></i> QUAY Láº I</a>
        <h2 class="fw-bold mt-2">Cháº¥m Äiá»ƒm BÃ i Ná»™p</h2>
    </div>

    <div class="glass-card">
        <form action="index.php?page=score-store" method="POST"> <?= csrfField() ?>
            <div class="row">
                <div class="col-md-12 mb-4">
                    <label class="form-label-custom">Chá»n sinh viÃªn & bÃ i ná»™p</label>
                    <select name="submission_id" class="form-select input-elegant" required>
                        <option value="">-- Chá»n bÃ i ná»™p --</option>
                        <?php if(!empty($submissions)): ?>
                            <?php foreach($submissions as $sub): ?>
                                <option value="<?= $sub['id'] ?>">
                                    <?= htmlspecialchars($sub['student_name']) ?> â€” Äá» tÃ i: <?= htmlspecialchars($sub['topic_title']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-4 mb-4">
                    <label class="form-label-custom">Äiá»ƒm sá»‘ (0 - 10)</label>
                    <input type="number" step="0.1" name="score" class="form-control input-elegant" placeholder="8.5" required>
                </div>

                <div class="col-md-12 mb-4">
                    <label class="form-label-custom">Nháº­n xÃ©t cá»§a giáº£ng viÃªn</label>
                    <textarea name="feedback" class="form-control" rows="4" style="border-radius: 15px;" placeholder="Nháº­p gÃ³p Ã½ cho sinh viÃªn..."></textarea>
                </div>
            </div>

            <input type="hidden" name="lecturer_id" value="<?= $_SESSION['user']['lecturer_id'] ?? '' ?>">

            <div class="pt-3">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow">LÆ°u Ä‘iá»ƒm sá»‘</button>
                <a href="index.php?page=scores" class="btn btn-light rounded-pill px-4 ms-2">Há»§y bá»</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
