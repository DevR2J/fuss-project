<?php
include 'includes/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email || !preg_match('/@flinders\.edu\.au$/', $email)) {
        $error = 'Invalid Flinders email address.';
    } else {
        // Check if email already exists
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->execute([$email]);
        if ($checkStmt->fetch()) {
            $error = 'Email already exists. Please use a different email.';
        } else {
            // Email is unique, proceed with registration
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, name, degree, college, academic_year, bio) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $email, $hash, $_POST['name'] ?? '', $_POST['degree'] ?? '',
                $_POST['college'] ?? '', $_POST['year'] ?? null, $_POST['bio'] ?? ''
            ]);
            header('Location: login.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Register • FUSS</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body class="auth-page">
        <main class="auth-container">
            <section class="card auth-card">
                <h1 class="brand">FUSS</h1>
                <h2 class="card-title">Create your account</h2>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error" role="alert"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST" class="form form-vertical" autocomplete="on">
                    <label class="form-label" for="email">Flinders Email</label>
                    <input class="input" type="email" id="email" name="email" placeholder="you@flinders.edu.au" required />

                    <label class="form-label" for="password">Password</label>
                    <input class="input" type="password" id="password" name="password" placeholder="Create a strong password" required />

                    <label class="form-label" for="name">Name</label>
                    <input class="input" type="text" id="name" name="name" required />

                    <label class="form-label" for="degree">Degree</label>
                    <input class="input" type="text" id="degree" name="degree" />

                    <label class="form-label" for="college">College</label>
                    <input class="input" type="text" id="college" name="college" />

                    <label class="form-label" for="year">Academic Year</label>
                    <input class="input" type="number" id="year" name="year" />

                    <label class="form-label" for="bio">Bio</label>
                    <textarea class="input" id="bio" name="bio" rows="3" placeholder="Tell others about your skills and interests"></textarea>

                    <button type="submit" class="btn btn-primary btn-block">Create account</button>
                </form>
                <p class="auth-switch">
                    Already have an account?
                    <a class="link" href="login.php">Sign in</a>
                </p>
            </section>
        </main>
    </body>
    </html>
