<?php
include 'includes/config.php';
include 'includes/auth.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Handle fulfilling a request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fulfill_request'])) {
    $request_id = $_POST['request_id'];
    $hours = floatval($_POST['hours']);
    
    // Get request details
    $stmt = $pdo->prepare("SELECT sr.*, u.name as requester_name FROM skills_requested sr JOIN users u ON sr.user_id = u.id WHERE sr.id = ?");
    $stmt->execute([$request_id]);
    $request = $stmt->fetch();
    
    if ($request && $hours > 0) {
        // Insert transactions - provider gains, requester loses
        $stmt2 = $pdo->prepare("INSERT INTO transactions (requester_id, provider_id, skill_name, hours, credits, type, service_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // Transaction for provider (gaining credits - Offered)
        $stmt2->execute([
            $request['user_id'], // requester
            $user_id, // provider (current user)
            $request['skill_name'],
            $hours,
            $hours,
            'Offered',
            date('Y-m-d')
        ]);
        // Transaction for requester (losing credits - Received)
        $stmt2->execute([
            $request['user_id'], // requester (requester)
            $user_id, // provider (current user)
            $request['skill_name'],
            $hours,
            $hours,
            'Received',
            date('Y-m-d')
        ]);
        
        // Update provider balance (add credits)
        $stmt3 = $pdo->prepare("UPDATE users SET fussc_balance = fussc_balance + ? WHERE id = ?");
        $stmt3->execute([$hours, $user_id]);
        
        // Update requester balance (subtract credits)
        $stmt4 = $pdo->prepare("UPDATE users SET fussc_balance = fussc_balance - ? WHERE id = ?");
        $stmt4->execute([$hours, $request['user_id']]);
        
        // Delete the request after fulfillment
        $stmt5 = $pdo->prepare("DELETE FROM skills_requested WHERE id = ?");
        $stmt5->execute([$request_id]);
        
        $success_message = "You successfully provided the service and earned $hours credits!";
    }
}

// Get all skill requests from other users
$stmt = $pdo->prepare("SELECT sr.*, u.name, u.email FROM skills_requested sr JOIN users u ON sr.user_id = u.id WHERE sr.user_id != ? ORDER BY sr.id DESC");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll();

// Get current user's own skill requests
$stmtMy = $pdo->prepare("SELECT * FROM skills_requested WHERE user_id = ? ORDER BY id DESC");
$stmtMy->execute([$user_id]);
$myRequests = $stmtMy->fetchAll();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Browse Skill Requests • Flinders Uni Skill Share (FUSS)</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <main class="page">
            <div class="container">
                <!-- My Skill Requests Section -->
                <?php if (count($myRequests) > 0): ?>
                    <section class="card profile-card" style="margin-bottom: 2rem;">
                        <h2 style="margin-top:0">My Skill Requests</h2>
                        <p style="color: var(--muted); margin-bottom: 1.5rem;">These are your pending requests waiting to be fulfilled.</p>
                        
                        <?php foreach ($myRequests as $req): ?>
                            <div class="card request-card my-request-card">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <span style="font-size: 1.5rem;">⏳</span>
                                    <div class="skill-title" style="margin-bottom: 0;"><?= htmlspecialchars($req['skill_name']) ?></div>
                                </div>
                                
                                <?php if (!empty($req['description'])): ?>
                                    <p style="color: var(--muted); margin-bottom: 0.5rem; padding-left: 2rem;"><?= htmlspecialchars($req['description']) ?></p>
                                <?php endif; ?>
                                
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem; padding: 0.75rem; background: var(--accent); border-radius: 6px;">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="flex-shrink: 0;">
                                        <circle cx="8" cy="8" r="7" stroke="var(--muted)" stroke-width="2"/>
                                        <path d="M8 4v4l3 3" stroke="var(--muted)" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    <span style="color: var(--muted); font-size: 0.9rem; font-style: italic;">
                                        Waiting for someone to fulfill this request...
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>
                
                <!-- Browse Other Users' Requests Section -->
                <section class="card profile-card">
                    <h2 style="margin-top:0">Browse Skill Requests</h2>
                    <p style="color: var(--muted); margin-bottom: 1.5rem;">Help other students by fulfilling their skill requests and earn credits!</p>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
                    <?php endif; ?>
                    
                    <?php if (count($requests) > 0): ?>
                        <?php foreach ($requests as $req): ?>
                            <div class="card request-card">
                                <div class="request-header">
                                    <span class="requester-name"><?= htmlspecialchars($req['name']) ?></span>
                                </div>

                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <div class="skill-title" style="margin-bottom: 0;"><?= htmlspecialchars($req['skill_name']) ?></div>
                                </div>

                                <?php if (!empty($req['description'])): ?>
                                    <p style="color: var(--muted); margin-bottom: 0.75rem; padding-left: 2rem;"><?= htmlspecialchars($req['description']) ?></p>
                                <?php endif; ?>

                                <div style="padding: 0.75rem; background: var(--accent); border-radius: 6px;">
                                    <form class="fulfill-form" method="POST" style="margin-top: 0; padding-top: 0; border-top: none;">
                                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>" />
                                        <div>
                                            <label for="hours-<?= $req['id'] ?>">Hours to provide</label>
                                            <input class="input" type="number" id="hours-<?= $req['id'] ?>" name="hours" min="0.25" step="0.25" placeholder="e.g. 1.5" required />
                                        </div>
                                        <button class="btn btn-primary" type="submit" name="fulfill_request">Fulfill Request</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: var(--muted); text-align: center; padding: 20px;">
                            No skill requests available at the moment. Check back later!
                        </p>
                    <?php endif; ?>
                </section>
            </div>
        </main>
    </body>
</html>
