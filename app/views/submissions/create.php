<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<style>
    .glass-card { background:#fff; border-radius:24px; box-shadow:0 10px 40px rgba(99,102,241,.08); border:1px solid #f1f5f9; }
    .form-label-custom { font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.6px; margin-bottom:8px; display:block; }
    .step-badge { width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; font-size:12px; font-weight:700; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; }
    .file-drop-zone { border:2px dashed #c7d2fe; border-radius:16px; padding:36px 24px; text-align:center; cursor:pointer; transition:all .2s; background:#fafbff; position:relative; }
    .file-drop-zone:hover, .file-drop-zone.dragover { border-color:#6366f1; background:#eef2ff; }
    .file-drop-zone input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
    .file-preview { display:none; align-items:center; gap:12px; background:#f0fdf4; border-radius:12px; padding:12px 16px; margin-top:12px; border:1px solid #bbf7d0; }
</style>

<div class="main-content" style="padding:32px; background:#f4f7fe; min-height:100vh;">
    <div class="mb-4">
        <a href="index.php?page=submissions" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> QUAY LẠI DANH SÁCH
        </a>
        <h2 class="fw-bold mt-2 mb-0" style="color:#0f172a">Nộp Bài Milestone</h2>
        <p class="text-muted small mt-1">Tải lên báo cáo / tài liệu cho cột mốc đồ án của bạn</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
            <i class="fa-solid fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="glass-card p-5">
                <form method="POST" action="index.php?page=submission-store" enctype="multipart/form-data">

                    <!-- STEP 1: Chọn đề tài -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="step-badge">1</span>
                            <span class="fw-bold" style="color:#1e293b">Chọn đề tài</span>
                        </div>
                        <label class="form-label-custom">Đề tài của bạn</label>
                        <select name="topic_id" class="form-select" style="height:50px;border-radius:14px;border:1.5px solid #e2e8f0" required>
                            <option value="">— Chọn đề tài —</option>
                            <?php foreach ($topics as $tp): ?>
                                <option value="<?= $tp['id'] ?>"><?= htmlspecialchars($tp['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- STEP 2: Chọn milestone -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="step-badge">2</span>
                            <span class="fw-bold" style="color:#1e293b">Chọn cột mốc đánh giá</span>
                        </div>
                        <label class="form-label-custom">Milestone</label>
                        <select name="milestone_id" class="form-select" style="height:50px;border-radius:14px;border:1.5px solid #e2e8f0" required>
                            <option value="">— Chọn cột mốc —</option>
                            <?php foreach ($milestones as $m): ?>
                                <option value="<?= $m['id'] ?>">
                                    <?= htmlspecialchars(ucfirst($m['title'])) ?>
                                    <?php if (!empty($m['semester_name'])): ?>— <?= htmlspecialchars($m['semester_name']) ?><?php endif; ?>
                                    <?php if (!empty($m['deadline'])): ?>(Hạn: <?= date('d/m/Y', strtotime($m['deadline'])) ?>)<?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <hr style="border-color:#f1f5f9; margin:28px 0">

                    <!-- STEP 3: Upload file -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="step-badge">3</span>
                            <span class="fw-bold" style="color:#1e293b">Tải lên tài liệu</span>
                        </div>
                        <label class="form-label-custom">File báo cáo (PDF, DOC, DOCX)</label>
                        <div class="file-drop-zone" id="dropZone">
                            <input type="file" name="report_file" id="fileInput" accept=".pdf,.doc,.docx" required>
                            <i class="fa-solid fa-cloud-arrow-up fa-2x mb-3 d-block" style="color:#a5b4fc"></i>
                            <p class="fw-bold mb-1" style="color:#4f46e5">Kéo thả file vào đây</p>
                            <p class="text-muted small mb-0">hoặc <span style="color:#6366f1;text-decoration:underline">bấm để chọn file</span></p>
                            <p class="text-muted" style="font-size:11px;margin-top:8px">PDF, DOC, DOCX — tối đa 10MB</p>
                        </div>
                        <div class="file-preview" id="filePreview">
                            <i class="fa-solid fa-file-circle-check fa-lg text-success"></i>
                            <div>
                                <div class="fw-bold small text-success" id="fileName">—</div>
                                <div class="text-muted" style="font-size:11px" id="fileSize">—</div>
                            </div>
                            <button type="button" class="btn btn-sm ms-auto text-danger p-0" id="clearFile">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn rounded-pill px-5 py-2 fw-bold flex-grow-1 text-white shadow-sm"
                                style="background:linear-gradient(135deg,#6366f1,#4f46e5);border:none;font-size:15px">
                            <i class="fa-solid fa-paper-plane me-2"></i>Nộp bài
                        </button>
                        <a href="index.php?page=submissions" class="btn btn-light rounded-pill px-4 py-2 fw-bold">Hủy</a>
                    </div>
                </form>
            </div>

            <div class="mt-4 p-4 rounded-3" style="background:#fffbeb;border:1px solid #fde68a">
                <div class="d-flex gap-3">
                    <i class="fa-solid fa-lightbulb text-warning mt-1"></i>
                    <div>
                        <p class="fw-bold small mb-1" style="color:#92400e">Lưu ý khi nộp bài</p>
                        <ul class="text-muted small mb-0 ps-3">
                            <li>File phải đúng định dạng PDF, DOC hoặc DOCX, tối đa 10MB.</li>
                            <li>Đảm bảo nộp trước thời hạn của cột mốc.</li>
                            <li>Nếu cần nộp lại, liên hệ giảng viên hướng dẫn.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const fileInput = document.getElementById('fileInput');
const dropZone  = document.getElementById('dropZone');
const filePreview = document.getElementById('filePreview');
const fileName  = document.getElementById('fileName');
const fileSize  = document.getElementById('fileSize');
const clearFile = document.getElementById('clearFile');

function showPreview(file) {
    fileName.textContent = file.name;
    fileSize.textContent = (file.size/1024/1024).toFixed(2) + ' MB';
    filePreview.style.display = 'flex';
    dropZone.style.borderColor = '#10b981';
    dropZone.style.background  = '#f0fdf4';
}
fileInput.addEventListener('change', () => { if (fileInput.files[0]) showPreview(fileInput.files[0]); });
clearFile.addEventListener('click', () => {
    fileInput.value = '';
    filePreview.style.display = 'none';
    dropZone.style.borderColor = '#c7d2fe';
    dropZone.style.background  = '#fafbff';
});
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', e => {
    e.preventDefault(); dropZone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) { const dt = new DataTransfer(); dt.items.add(file); fileInput.files = dt.files; showPreview(file); }
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
