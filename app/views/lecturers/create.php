<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content" style="padding: 30px; background-color: #f8fafc;">
    <div class="mb-4">
        <a href="index.php?page=lecturers" class="text-decoration-none text-muted small"><i class="fa-solid fa-arrow-left me-1"></i> Back to list</a>
        <h2 class="fw-bold mt-2">Add New Lecturer</h2>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; max-width: 600px; border: 1px solid #f1f5f9;">
        <form action="index.php?page=lecturer-store" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">FULL NAME</label>
                <input type="text" name="full_name" class="form-control rounded-pill px-3" placeholder="Enter lecturer's name" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">EMAIL ADDRESS (Will create new account)</label>
                <input type="email" name="email" class="form-control rounded-pill px-3" placeholder="example@vnu.edu.vn" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">EXPERTISE</label>
                <input type="text" name="expertise" class="form-control rounded-pill px-3" placeholder="e.g. Artificial Intelligence">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Save Lecturer</button>
                <a href="index.php?page=lecturers" class="btn btn-light rounded-pill px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require '../app/views/layouts/footer.php'; ?>