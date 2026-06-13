<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Reset Password</title>

    <link rel="stylesheet"
          href="assets/css/style.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>

<body class="auth-body">

<div class="auth-container">

    <div class="auth-card">

        <div class="auth-header">

            <h2>Reset Password</h2>

        </div>

        <?php if(isset($error)): ?>

            <div class="alert alert-danger">

                <?= $error ?>

            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">

                <label>New Password</label>

                <input type="password"
                       name="password"
                       class="form-control"
                       required>

            </div>

            <div class="mb-3">

                <label>Confirm Password</label>

                <input type="password"
                       name="confirm_password"
                       class="form-control"
                       required>

            </div>

            <button type="submit"
                    class="btn btn-primary w-100">

                Reset Password

            </button>

        </form>

    </div>

</div>

</body>
</html>