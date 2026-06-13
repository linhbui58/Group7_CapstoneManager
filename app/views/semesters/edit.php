<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 40px; background-color: #f4f7fe;">
    <div class="mb-4">
        <a href="index.php?page=semesters" class="text-decoration-none text-muted small"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
        <h2 class="fw-bold mt-2">Edit Semester: <?= htmlspecialchars($semester['name']) ?></h2>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 600px;">
        <form action="index.php?page=semester-update&id=<?= $semester['id'] ?>" method="POST">
            <div class="mb-3">
                <label class="fw-bold small text-muted">SEMESTER NAME</label>
                <input type="text" name="name" class="form-control rounded-pill px-3" value="<?= htmlspecialchars($semester['name']) ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold small text-muted">START DATE</label>
                    <input type="date" name="start_date" class="form-control rounded-pill px-3" value="<?= $semester['start_date'] ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold small text-muted">END DATE</label>
                    <input type="date" name="end_date" class="form-control rounded-pill px-3" value="<?= $semester['end_date'] ?>" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold text-white shadow-sm" style="border: none;">Update Semester</button>
                <a href="index.php?page=semesters" class="btn btn-light rounded-pill px-4 ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>