<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe;">
    <h2 class="fw-bold mb-4">Update Evaluation</h2>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 600px;">
        <form action="index.php?page=score-update&id=<?= $score['id'] ?>" method="POST">
            <div class="mb-3">
                <label class="fw-bold small text-muted">STUDENT NAME</label>
                <input type="text" class="form-control rounded-pill bg-light" value="<?= htmlspecialchars($score['student_name']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-muted">CURRENT SCORE</label>
                <input type="number" step="0.1" min="0" max="10" name="score" class="form-control rounded-pill" value="<?= $score['score'] ?>" required>
            </div>

            <div class="mb-4">
                <label class="fw-bold small text-muted">FEEDBACK</label>
                <textarea name="feedback" class="form-control" rows="4" style="border-radius: 15px;"><?= htmlspecialchars($score['feedback']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold text-white shadow-sm">Update Score</button>
            <a href="index.php?page=scores" class="btn btn-light rounded-pill px-4 ms-2">Cancel</a>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>