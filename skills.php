<?php
include 'includes/config.php';
include 'includes/auth.php';
requireLogin();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
    $type = $_POST['type'];
    if ($type === 'offered') {
        $stmt = $pdo->prepare("INSERT INTO skills_offered (user_id, skill_name, description, category, topic_degree) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $user_id,
            $_POST['skill'] ?? '',
            $_POST['desc'] ?? '',
            $_POST['category'] ?? null,
            $_POST['topic'] ?? null
        ]);
        // Do not create a transaction when offering a skill. Transactions are created when a request is fulfilled.
    } elseif ($type === 'requested') {
        $stmt = $pdo->prepare("INSERT INTO skills_requested (user_id, skill_name, description) VALUES (?, ?, ?)");
        $stmt->execute([
            $user_id,
            $_POST['skill'] ?? '',
            $_POST['desc'] ?? ''
        ]);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
            <main class="page">
                <div class="container stack">
                <section class="card profile-card">
                    <h2 style="margin-top:0">Offer a Skill</h2>
                    <form class="form form-vertical" method="POST">
                        <input type="hidden" name="type" value="offered" />

                        <label class="form-label" for="offered-skill">Skill</label>
                        <input class="input" type="text" id="offered-skill" name="skill" required />

                        <label class="form-label" for="offered-desc">Description</label>
                        <textarea class="input" id="offered-desc" name="desc" rows="3"></textarea>

                        <label class="form-label" for="offered-category">Category</label>
                        <select class="input" id="offered-category" name="category">
                            <option value="Academic">Academic</option>
                            <option value="Tech Support">Tech Support</option>
                            <option value="Life Skills">Life Skills</option>
                        </select>

                        <label class="form-label" for="offered-topic">Topic/Degree</label>
                        <input class="input" type="text" id="offered-topic" name="topic" required />

                        <label class="form-label" for="offered-hours">Hours</label>
                        <input class="input" type="number" id="offered-hours" name="hours" min="0.25" step="0.25" placeholder="e.g. 1.5" required />

                        <button class="btn btn-primary">Submit</button>
                    </form>
                </section>

                <section class="card profile-card">
                    <h2 style="margin-top:0">Request a Skill</h2>
                    <form class="form form-vertical" method="POST">
                        <input type="hidden" name="type" value="requested" />

                        <label class="form-label" for="requested-skill">Skill</label>
                        <input class="input" type="text" id="requested-skill" name="skill" required />

                        <label class="form-label" for="requested-desc">Description</label>
                        <textarea class="input" id="requested-desc" name="desc" rows="3"></textarea>

                        <button class="btn btn-primary">Submit Request</button>
                    </form>
                </section>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var category = document.getElementById('offered-category');
                    var topic = document.getElementById('offered-topic');
                    function updateTopicRequired() {
                        if (category.value === 'Academic') {
                            topic.required = true;
                            topic.placeholder = 'Required for Academic';
                        } else {
                            topic.required = false;
                            topic.placeholder = '';
                        }
                    }
                    category.addEventListener('change', updateTopicRequired);
                    updateTopicRequired();
                });
                </script>
            </div>
        </main>
    </body>
</html>
