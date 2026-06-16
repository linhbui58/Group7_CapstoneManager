<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=registrations" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-arrow-left me-1"></i> Trá»Ÿ vá» danh sÃ¡ch
        </a>
        <h2 class="fw-bold mt-2">ÄÄƒng KÃ½ Äá» TÃ i</h2>
        <p class="text-muted small">Chá»n Ä‘á» tÃ i Ä‘Ã£ Ä‘Æ°á»£c duyá»‡t vÃ  giáº£ng viÃªn hÆ°á»›ng dáº«n mong muá»‘n.</p>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
                <form action="index.php?page=registration-store" method="POST"> <?= csrfField() ?>

                    <!-- Chá»n Ä‘á» tÃ i -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">CHá»ŒN Äá»€ TÃ€I <span class="text-danger">*</span></label>
                        <?php if (empty($topics)): ?>
                            <div class="alert alert-warning rounded-3 small">
                                Hiá»‡n chÆ°a cÃ³ Ä‘á»  tÃ i nÃ o Ä‘Æ°á»£c duyá»‡t. Vui lÃ²ng quay láº¡i sau.
                            </div>
                        <?php else: ?>
                            <select name="topic_id" class="form-select rounded-pill" required>
                                <option value="">-- Chá» n Ä‘á»  tÃ i --</option>
                                <?php foreach ($topics as $t): ?>
                                    <option value="<?= $t['id'] ?>" data-keywords="<?= htmlspecialchars(strtolower($t['keywords'] ?? '')) ?>">
                                        <?= htmlspecialchars($t['title']) ?>
                                        <?= !empty($t['keywords']) ? '— ' . htmlspecialchars($t['keywords']) : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <!-- Há»c ká»³ -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Há»ŒC Ká»² <span class="text-danger">*</span></label>
                        <select name="semester_id" class="form-select rounded-pill" required>
                            <option value="">-- Chá»n há»c ká»³ --</option>
                            <?php foreach ($semesters as $sem): ?>
                                <option value="<?= $sem['id'] ?>"><?= htmlspecialchars($sem['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Giáº£ng viÃªn mong muá»‘n -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">GIáº¢NG VIÃŠN HÆ¯á»šNG DáºªN MONG MUá»N</label>
                        <select name="desired_lecturer_id" class="form-select rounded-pill">
                            <option value="">-- KhÃ´ng chá»‰ Ä‘á»‹nh --</option>
                            <?php foreach ($lecturers as $l): ?>
                                <option value="<?= $l['id'] ?>" data-expertise="<?= htmlspecialchars(strtolower($l['expertise'] ?? '')) ?>">
                                    <?= htmlspecialchars($l['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Ghi chÃº / keywords -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">GHI CHÃš / KEYWORDS</label>
                        <textarea name="keywords" class="form-control" style="border-radius: 15px;" rows="3"
                                  placeholder="Nháº­p cÃ¡c tá»« khÃ³a hoáº·c ghi chÃº liÃªn quan..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm"
                                <?= empty($topics) ? 'disabled' : '' ?>>
                            XÃ¡c Nháº­n ÄÄƒng KÃ½
                        </button>
                        <a href="index.php?page=registrations" class="btn btn-light rounded-pill px-4">Há»§y</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('select[name="topic_id"]').addEventListener('change', function() {
    let selectedTopic = this.options[this.selectedIndex];
    let topicKeywordsRaw = selectedTopic.getAttribute('data-keywords') || '';
    let topicKeywords = topicKeywordsRaw ? topicKeywordsRaw.split(',').map(s => s.trim()).filter(k => k) : [];
    
    let lecturerSelect = document.querySelector('select[name="desired_lecturer_id"]');
    let defaultOption = lecturerSelect.options[0];
    let lecturers = Array.from(lecturerSelect.options).slice(1);
    
    lecturers.forEach(opt => {
        let expRaw = opt.getAttribute('data-expertise') || '';
        let exp = expRaw ? expRaw.split(',').map(s => s.trim()).filter(e => e) : [];
        let score = 0;
        
        topicKeywords.forEach(k => {
            if (exp.some(e => e.includes(k) || k.includes(e))) {
                score++;
            }
        });
        
        opt.setAttribute('data-score', score);
        
        let originalText = opt.text.replace(/ \(Match Score: \d+\)/, '');
        if (score > 0) {
            opt.text = originalText + ` (Match Score: ${score})`;
        } else {
            opt.text = originalText;
        }
    });
    
    lecturers.sort((a, b) => parseInt(b.getAttribute('data-score')) - parseInt(a.getAttribute('data-score')));
    
    lecturerSelect.innerHTML = '';
    lecturerSelect.appendChild(defaultOption);
    lecturers.forEach(opt => lecturerSelect.appendChild(opt));
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
