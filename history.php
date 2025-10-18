<?php
include 'includes/config.php';
include 'includes/auth.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE requester_id=? OR provider_id=? ORDER BY service_date DESC");
$stmt->execute([$user_id, $user_id]);
$history = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>History • FUSS</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main class="page">
      <div class="container">
        <section class="card profile-card">
          <h2 style="margin-top:0">Transaction History</h2>
          <?php if (count($history) > 0): ?>
            <table>
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Type</th>
                  <th>Service</th>
                  <th>Hours</th>
                  <th>Credits</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($history as $t): ?>
                  <tr>
                    <td><?= htmlspecialchars($t['service_date'] ?? '') ?></td>
                    <td><?= htmlspecialchars($t['type'] ?? '') ?></td>
                    <td><?= htmlspecialchars($t['skill_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($t['hours'] ?? 0) ?></td>
                    <td><?= htmlspecialchars($t['credits'] ?? 0) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p style="color: var(--muted); text-align: center; padding: 20px;">
              No transactions yet. Start exchanging skills to build your history!
            </p>
          <?php endif; ?>
        </section>
      </div>
    </main>
  </body>
</html>
