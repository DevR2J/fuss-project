<?php
include 'includes/config.php';
include 'includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
                exit;
        } else {
                $error = 'Invalid email or password.';
        }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login • FUSS</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body class="auth-page">
        <main class="auth-container">
            <section class="card auth-card">
                <h1 class="brand">FUSS</h1>
                <h2 class="card-title">Welcome back</h2>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error" role="alert"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST" class="form form-vertical" autocomplete="on">
                    <label class="form-label" for="email">Email</label>
                    <input class="input" type="email" id="email" name="email" placeholder="you@flinders.edu.au" required />

                    <label class="form-label" for="password">Password</label>
                    <input class="input" type="password" id="password" name="password" placeholder="••••••••" required />

                    <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                </form>
                <p class="auth-switch">
                    Don&#39;t have an account?
                    <a class="link" href="register.php">Sign up</a>
                </p>
            </section>
        </main>
    </body>
    </html>
