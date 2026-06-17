<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <div class="form-container">

        <h2>Tạo người dùng</h2>

        <?php if(isset($error)): ?>

            <div class="alert alert-danger">

                <?= $error ?>

            </div>

        <?php endif; ?>

        <form method="POST" action="index.php?page=user-store"> <?= csrfField() ?>

            <div class="mb-3">

                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       required>

            </div>

            <div class="mb-3">

                <label>Mật khẩu</label>

                <input type="password"
                       name="password"
                       class="form-control"
                       required>

            </div>

            <div class="mb-3">

                <label>Vai trò</label>

                <select name="role"
                        class="form-control"
                        required>

                    <option value="">
                        -- Chọn vai trò --
                    </option>

                    <option value="student">
                        Student
                    </option>

                    <option value="lecturer">
                        Lecturer
                    </option>

                </select>

            </div>

            <button type="submit"
                    class="btn btn-success">

                Tạo người dùng

            </button>

        </form>

    </div>

</div>

<?php require '../app/views/layouts/footer.php'; ?>
