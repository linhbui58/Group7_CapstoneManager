<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f8fafc;">
    <h2 class="fw-bold mb-4">Táº¡o PhÃ¢n CÃ´ng Má»›i</h2>
    
    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 700px;">
        <form action="index.php?page=assignment-store" method="POST"> <?= csrfField() ?>
            <div class="mb-4">
                <label class="fw-bold small text-muted mb-2">CHá»ŒN Äá»€ TÃ€I</label>
                <select name="topic_id" class="form-select rounded-pill px-3" required style="height: 50px;">
                    <option value="">-- Chá»n Ä‘á» tÃ i --</option>
                    <?php foreach($topics as $topic): ?>
                        <option value="<?= $topic['id'] ?>"><?= htmlspecialchars($topic['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="fw-bold small text-muted mb-2">GIáº¢NG VIÃŠN PHá»¤ TRÃCH</label>
                <select name="lecturer_id" class="form-select rounded-pill px-3" required style="height: 50px;">
                    <option value="">-- Chá»n giáº£ng viÃªn --</option>
                    <?php foreach($lecturers as $l): ?>
                        <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow">LÆ°u PhÃ¢n CÃ´ng</button>
                <a href="index.php?page=assignments" class="btn btn-light rounded-pill px-4 ms-2">Há»§y</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>
