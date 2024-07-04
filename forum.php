<?php
include 'common.php';

// Fetch topics
$topics = [];
$stmt = $conn->prepare("SELECT topics.id, topics.title, topics.image, topics.view_count, topics.comment_count, users.nickname FROM topics JOIN users ON topics.user_id = users.id");
$stmt->execute();
$result = $stmt->get_result();
while ($topic = $result->fetch_assoc()) {
    $topics[] = $topic;
}
$stmt->close();

// Add new topic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_topic'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $user = getUser();
    $user_id = getUserId($user);
    $image = '';

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $stmt = $conn->prepare("INSERT INTO topics (title, description, user_id, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $title, $description, $user_id, $image);
    $stmt->execute();
    $stmt->close();
    header('Location: forum.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #d3d3d3; /* Šedé pozadí */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container {
            border: 2px solid #aaa;
            padding: 10px;
            background-color: #d3d3d3; /* Šedé pozadí */
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-container h2 {
            text-align: center;
            background-color: #bbb;
            padding: 10px;
            border-radius: 8px;
            margin-top: 0;
        }
        .topic-container {
            border: 2px solid #aaa;
            padding: 10px;
            background-color: #d3d3d3; /* Šedé pozadí */
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .topic {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding: 5px 0;
            font-size: 0.9em;
        }
        .topic-info {
            flex-grow: 1;
        }
        .topic h3 {
            margin: 0;
            font-size: 1em;
        }
        .topic p {
            margin: 0;
            color: gray;
        }
        .topic img {
            max-width: 100px;
            height: auto;
            margin-left: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
        }
        .file-upload {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .file-upload input[type="file"] {
            flex-grow: 1;
            margin-right: 10px;
        }
        button {
            background-color: #333;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            transition: transform 0.3s, opacity 0.3s;
        }
        button:hover {
            transform: scale(1.05);
        }
        button:active {
            opacity: 0.6;
        }
        .header {
            background-color: #bbb;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 10px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <?php renderHeader(); ?>
    <div class="container">
        <div class="form-container">
            <h2>Diskuze</h2>
            <?php if (getUser()): ?>
                <form method="POST" action="forum.php" enctype="multipart/form-data" onsubmit="handleFormSubmit(event)">
                    <input type="text" name="title" placeholder="Nové téma" required><br>
                    <textarea name="description" placeholder="Úvodní slova diskuze" required></textarea><br>
                    <div class="file-upload">
                        <label for="image">Zvolit obrazek tématu:</label>
                        <input type="file" name="image" id="image">
                    </div>
                    <button type="submit" name="new_topic" id="submit-button">Přidat Téma</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="topic-container">
            <div class="header">Témata:</div>
            <?php if (empty($topics)): ?>
                <p>Žádné témata.</p>
            <?php else: ?>
                <?php foreach ($topics as $topic): ?>
                    <div class="topic">
                        <div class="topic-info">
                            <h3><a href="topic.php?id=<?php echo $topic['id']; ?>"><?php echo htmlspecialchars($topic['title']); ?></a></h3>
                            <p>Autor: <?php echo htmlspecialchars($topic['nickname']); ?></p>
                            <p>Komentáře: <?php echo htmlspecialchars($topic['comment_count']); ?>, Zobrazení: <?php echo htmlspecialchars($topic['view_count']); ?></p>
                        </div>
                        <?php if ($topic['image']): ?>
                            <img src="<?php echo htmlspecialchars($topic['image']); ?>" alt="Obrazek tematu">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function handleFormSubmit(event) {
            event.preventDefault();
            const button = document.getElementById('submit-button');
            button.style.transition = 'transform 0.5s, opacity 0.5s';
            button.style.transform = 'scale(0.1)';
            button.style.opacity = '0';
            setTimeout(() => {
                event.target.submit();
            }, 500);
        }
    </script>
</body>
</html>
