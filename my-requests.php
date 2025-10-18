<?php
include 'includes/config.php';
include 'includes/auth.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Get current user's skill requests
$stmt = $pdo->prepare("SELECT * FROM skills_requested WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$myRequests = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>My Skill Requests • Flinders Uni Skill Share (FUSS)</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <main class="page">
            <div class="container">
                <section class="card profile-card">
                    <h2 style="margin-top:0">My Skill Requests</h2>
                    <p style="color: var(--muted); margin-bottom: 1.5rem;">View all the skills you've requested. Other users can see and fulfill these requests.</p>
                    
                    <?php if (count($myRequests) > 0): ?>
                        <?php foreach ($myRequests as $req): ?>
                            <div class="card request-card">
                                <div class="skill-title"><?= htmlspecialchars($req['skill_name']) ?></div>
                                
                                <?php if (!empty($req['description'])): ?>
                                    <p style="color: var(--muted); margin-bottom: 0;"><?= htmlspecialchars($req['description']) ?></p>
                                <?php endif; ?>
                                
                                <p style="color: var(--muted); font-size: 0.9rem; margin-top: 0.5rem; margin-bottom: 0;">
                                    <em>Waiting for someone to fulfill this request...</em>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: var(--muted); text-align: center; padding: 20px;">
                            You haven't requested any skills yet. Go to the Skills page to request help!
                        </p>
                    <?php endif; ?>
                </section>
            </div>
        </main>
    </body>
</html>
