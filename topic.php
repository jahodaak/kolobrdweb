<?php
include 'common.php';
include 'header.php';

// Get topic id
$topic_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Increment view count
$stmt = $conn->prepare("UPDATE topics SET view_count = view_count + 1 WHERE id = ?");
$stmt->bind_param("i", $topic_id);
$stmt->execute();
$stmt->close();

// Fetch topic
$stmt = $conn->prepare("SELECT topics.title, topics.description, topics.image, topics.user_id, users.nickname FROM topics JOIN users ON topics.user_id = users.id WHERE topics.id = ?");
$stmt->bind_param("i", $topic_id);
$stmt->execute();
$stmt->bind_result($title, $description, $image, $user_id, $nickname);
$stmt->fetch();
$stmt->close();

// Fetch comments
$comments = [];
$stmt = $conn->prepare("SELECT comments.id, comments.comment, comments.image, users.nickname FROM comments JOIN users ON comments.user_id = users.id WHERE comments.topic_id = ? ORDER BY comments.id");
$stmt->bind_param("i", $topic_id);
$stmt->execute();
$result = $stmt->get_result();
while ($comment = $result->fetch_assoc()) {
    $comments[] = $comment;
}
$stmt->close();

// Add new comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_comment'])) {
    $comment_text = htmlspecialchars($_POST['comment']);
    $user = getUser();
    $user_id = getUserId($user);
    $comment_image = '';

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] === UPLOAD_ERR_OK) {
        $comment_image = 'uploads/' . basename($_FILES['comment_image']['name']);
        move_uploaded_file($_FILES['comment_image']['tmp_name'], $comment_image);
    }

    $stmt = $conn->prepare("INSERT INTO comments (topic_id, user_id, comment, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $topic_id, $user_id, $comment_text, $comment_image);
    $stmt->execute();
    $stmt->close();

    // Update comment count
    $stmt = $conn->prepare("UPDATE topics SET comment_count = comment_count + 1 WHERE id = ?");
    $stmt->bind_param("i", $topic_id);
    $stmt->execute();
    $stmt->close();

    header("Location: topic.php?id=$topic_id");
    exit();
}

// Rate topic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rate_topic'])) {
    $rating = intval($_POST['rating']);
    $user = getUser();
    $user_id = getUserId($user);

    $stmt = $conn->prepare("INSERT INTO ratings (topic_id, user_id, rating) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE rating = ?");
    $stmt->bind_param("iiii", $topic_id, $user_id, $rating, $rating);
    $stmt->execute();
    $stmt->close();
    header("Location: topic.php?id=$topic_id");
    exit();
}

// Fetch ratings
$average_rating = 0;
$stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM ratings WHERE topic_id = ?");
$stmt->bind_param("i", $topic_id);
$stmt->execute();
$stmt->bind_result($average_rating);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .section {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fafafa;
        }
        .section h2 {
            margin-top: 0;
        }
        .comment-section {
            margin-top: 30px;
        }
        .comment {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .comment p {
            margin: 0;
        }
        .comment span {
            font-size: small;
            color: gray;
        }
        .comment img {
            max-width: 100%;
            height: auto;
        }
        .rating {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .rating span {
            font-size: 1.5em;
            margin-right: 10px;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 1.5em;
            color: gold;
            cursor: pointer;
        }
        .rating label:hover,
        .rating label:hover ~ label {
            color: darkorange;
        }
        .rating input:checked ~ label {
            color: darkorange;
        }
        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
        }
        button {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: transform 0.3s, opacity 0.3s;
        }
        button:hover {
            transform: scale(1.05);
        }
        button:active {
            opacity: 0.6;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="section">
            <h2><?php echo htmlspecialchars($title); ?></h2>
            <p>Autor: <?php echo htmlspecialchars($nickname); ?></p>
            <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
            <?php if ($image): ?>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="Obrazek tematu">
            <?php endif; ?>
        </div>
        
        <div class="section rating">
            <span>Hodnoceni:</span>
            <form method="POST" action="topic.php?id=<?php echo $topic_id; ?>">
                <input type="hidden" name="rate_topic" value="1">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" name="rating" id="rating<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if (round($average_rating) == $i)
                              echo 'checked'; ?>>
                    <label for="rating<?php echo $i; ?>">&#9733;</label>
                <?php endfor; ?>
                <button type="submit">Ohodnotit</button>
            </form>
        </div>
        
        <?php if (getUser()): ?>
            <div class="section">
                <form method="POST" action="topic.php?id=<?php echo $topic_id; ?>" enctype="multipart/form-data">
                    <textarea name="comment" placeholder="Komentar" required></textarea><br>
                    <input type="file" name="comment_image"><br>
                    <button type="submit" name="new_comment">Pridat Komentar</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="section comment-section">
            <?php if (empty($comments)): ?>
                <p>Zadne komentare.</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                        <span>Autor: <?php echo htmlspecialchars($comment['nickname']); ?></span>
                        <?php if ($comment['image']): ?>
                            <p><img src="<?php echo htmlspecialchars($comment['image']); ?>" alt="Foto"></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
