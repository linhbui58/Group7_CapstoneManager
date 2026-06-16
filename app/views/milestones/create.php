<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc; min-height: 100vh;">
    <div class="mb-4">
        <a href="index.php?page=milestones" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> TRá»ž Vá»€ DANH SÃCH
        </a>
        <h2 class="fw-bold mt-2" style="color: #0f172a;">Thiáº¿t Láº­p Cá»™t Má»‘c Má»›i</h2>
    </div>

    <div class="card border-0 shadow-sm p-5 mx-auto" style="border-radius: 25px; max-width: 600px;">
        <form action="index.php?page=milestone-store" method="POST"> <?= csrfField() ?>
            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">GIAI ÄOáº N ÄÃNH GIÃ</label>
                <select name="title" class="form-select rounded-pill px-3 shadow-none" style="height: 50px;" required>
                    <option value="proposal">Äá» cÆ°Æ¡ng nghiÃªn cá»©u (Proposal)</option>
                    <option value="midterm">BÃ¡o cÃ¡o tiáº¿n Ä‘á»™ (Midterm)</option>
                    <option value="final">BÃ¡o cÃ¡o nghiá»‡m thu (Final)</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">ÃP Dá»¤NG CHO Há»ŒC Ká»²</label>
                <select name="semester_id" class="form-select rounded-pill px-3 shadow-none" style="height: 50px;" required>
                    <option value="">-- Chá»n há»c ká»³ --</option>
                    <?php foreach($semesters as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5">
                <label class="form-label fw-bold small text-muted">THá»œI Háº N KHÃ“A Cá»”NG Ná»˜P BÃ€I</label>
                <input type="datetime-local" name="deadline" class="form-control rounded-pill px-3 shadow-none" style="height: 50px;" required>
                <p class="form-text small text-danger mt-2 ms-2"><i class="fa-solid fa-circle-info"></i> Sau thá»i gian nÃ y, sinh viÃªn sáº½ khÃ´ng thá»ƒ ná»™p bÃ i vÃ o há»‡ thá»‘ng.</p>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow">
                    KÃCH HOáº T Cá»˜T Má»C
                </button>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
