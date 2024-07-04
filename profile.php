<?php
include 'common.php';
if (!getUser()) {
    header('Location: login.php');
    exit();
}
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $image = 'uploads/profiles/' . basename($_FILES['profile_image']['name']);
    if (!is_dir('uploads/profiles')) {
        mkdir('uploads/profiles', 0777, true);
    }
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $image);
    // Update user's profile image in the database
    $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE nickname = ?");
    $stmt->bind_param("ss", $image, getUser());
    $stmt->execute();
    $stmt->close();
}
// Get user details
$user = getUser();
$user_id = getUserId($user);
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Můj Profil</title>
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
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php renderHeader(); ?>
    <div class="container">
        <h2>Profil uživatele: <?php echo htmlspecialchars($user); ?></h2>
        <?php if ($user_data['profile_image']): ?>
        <img src="<?php echo htmlspecialchars($user_data['profile_image']); ?>" alt="Profilová fotka" style="max-width: 200px;"><br><br>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="profile_image">Profilová fotka:</label><br>
            <input type="file" id="profile_image" name="profile_image"><br><br>
            <input type="submit" value="Nahrát fotku">
        </form>
        <form method="POST">
            <input type="submit" name="logout" value="Odhlásit">
        </form>
    </div>
</body>
</html>
