<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

    <link rel="stylesheet"
          href="assets/css/style.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>

<body class="auth-body">

<div class="auth-container">

    <div class="auth-card">

        <div class="auth-header">

            <h2>Forgot Password</h2>

            <p>Enter your email</p>

        </div>

        <?php if(isset($success)): ?>

            <div class="alert alert-success">

                <?= $success ?>

            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">

                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       required>

            </div>

            <button type="submit"
                    class="btn btn-warning w-100">

                Send Reset Link

            </button>

        </form>

        <div class="auth-footer">

            <a href="index.php?page=login">

                Back to Login

            </a>

        </div>

    </div>

</div>

</body>
</html>