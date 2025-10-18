<?php
include 'includes/config.php';
include 'includes/auth.php';
requireLogin();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFilename = null;
    if (isset($_FILES['pic']) && is_uploaded_file($_FILES['pic']['tmp_name']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
        $original = basename($_FILES['pic']['name']);
        $safe = preg_replace('/[^A-Za-z0-9._-]/', '_', $original);
        $unique = time() . '_' . $safe;
        $destPath = __DIR__ . '/uploads/' . $unique;
        if (move_uploaded_file($_FILES['pic']['tmp_name'], $destPath)) {
            $newFilename = $unique;
        }
    }

    $stmt = $pdo->prepare("UPDATE users SET name=?, degree=?, college=?, academic_year=?, bio=?, profile_pic = COALESCE(?, profile_pic) WHERE id=?");
    $stmt->execute([
        $_POST['name'] ?? '',
        $_POST['degree'] ?? '',
        $_POST['college'] ?? '',
        $_POST['year'] ?? null,
        $_POST['bio'] ?? '',
        $newFilename,
        $user_id
    ]);
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Recalculate balance from transactions

$stmtBalance = $pdo->prepare("
    SELECT
        COALESCE(SUM(CASE WHEN provider_id = ? AND type = 'Offered' THEN credits ELSE 0 END), 0)
        - COALESCE(SUM(CASE WHEN requester_id = ? AND type = 'Received' THEN credits ELSE 0 END), 0) AS balance
    FROM transactions
");
$stmtBalance->execute([$user_id, $user_id]);
$balanceRow = $stmtBalance->fetch();
$actualBalance = $balanceRow['balance'];

// Update the user's balance in the database if it's different
if ($actualBalance != $user['fussc_balance']) {
    $updateStmt = $pdo->prepare("UPDATE users SET fussc_balance = ? WHERE id = ?");
    $updateStmt->execute([$actualBalance, $user_id]);
    $user['fussc_balance'] = $actualBalance;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/style.css">
        <script defer src="js/profile.js?v=1"></script>
    </head>
    <body>
<?php include 'includes/header.php'; ?>
<main class="page">
    <div class="container profile-wrapper">
        <aside class="card profile-card">
            <img class="avatar" src="uploads/<?= htmlspecialchars($user['profile_pic'] ?? '') ?>" alt="Profile picture" onerror="this.src='https://via.placeholder.com/130x130?text=Avatar'">
            <div class="balance">FUSS Credit Balance: <?= number_format($user['fussc_balance'] ?? 0, 2) ?></div>
        </aside>

            <section class="card profile-card">
                <h2 style="margin-top:0">Profile</h2>
                <form id="profileForm" class="form form-vertical readonly" method="POST" enctype="multipart/form-data">
                <label class="form-label" for="name">Name</label>
                <input class="input" type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>">

                <label class="form-label" for="degree">Degree</label>
                <input class="input" type="text" id="degree" name="degree" value="<?= htmlspecialchars($user['degree'] ?? '') ?>">

                <label class="form-label" for="college">College</label>
                <input class="input" type="text" id="college" name="college" value="<?= htmlspecialchars($user['college'] ?? '') ?>">

                <label class="form-label" for="year">Academic Year</label>
                <input class="input" type="number" id="year" name="year" value="<?= htmlspecialchars($user['academic_year'] ?? '') ?>">

                <label class="form-label" for="bio">Bio</label>
                <textarea class="input" id="bio" name="bio" rows="4"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

                        <label class="form-label" for="pic">Profile picture</label>
                        <input class="input" type="file" id="pic" name="pic" accept="image/*">

                        <div class="profile-actions">
                            <button id="editBtn" class="btn btn-primary" type="button">Edit</button>
                            <button id="saveBtn" class="btn btn-primary hidden" type="submit" disabled>Save</button>
                            <button id="cancelBtn" class="btn btn-ghost hidden" type="button" disabled>Cancel</button>
                        </div>
                    </form>
        </section>
    </div>
</main>
    </body>
</html>
