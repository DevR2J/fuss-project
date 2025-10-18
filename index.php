<?php
require_once 'includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Home • FUSS</title>
		<link rel="stylesheet" href="css/style.css" />
	</head>
	<body>
		<?php include 'includes/header.php'; ?>
		<main class="page">
			<div class="container">
				<section class="hero card">
					<h1 class="hero-title">Flinders Uni Skill Share</h1>
					<p class="hero-subtitle">Exchange skills and services with fellow students using FUSScredits. Learn, help, and grow together.</p>
					<div class="hero-actions">
						<a class="btn btn-primary" href="skills.php">Explore Skills</a>
						<a class="btn btn-primary" href="profile.php">View Profile</a>
					</div>
				</section>
			</div>

			<section class="container grid-3 features" style="margin-top: 20px;">
				<a class="card feature-card" href="skills.php">
					<h3>Offer a Skill</h3>
					<p>Share what you’re good at and earn FUSScredits by helping others.</p>
				</a>
				<a class="card feature-card" href="history.php">
					<h3>View History</h3>
					<p>Track your exchanges, hours, and credit balance over time.</p>
				</a>
			</section>
		</main>
	</body>
</html>
