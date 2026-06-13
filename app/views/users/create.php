<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <div class="form-container">

        <h2>Create User</h2>

        <?php if(isset($error)): ?>

            <div class="alert alert-danger">

                <?= $error ?>

            </div>

        <?php endif; ?>

        <form method="POST" action="index.php?page=user-store">

            <div class="mb-3">

                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       required>

            </div>

            <div class="mb-3">

                <label>Password</label>

                <input type="password"
                       name="password"
                       class="form-control"
                       required>

            </div>

            <div class="mb-3">

                <label>Role</label>

                <select name="role"
                        class="form-control"
                        required>

                    <option value="">
                        -- Select Role --
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

                Create User

            </button>

        </form>

    </div>

</div>

<?php require '../app/views/layouts/footer.php'; ?>