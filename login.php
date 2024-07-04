<?php
include_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = htmlspecialchars($_POST['nickname']);
    $user = getUserByNickname($nickname);

    if ($user) {
        $_SESSION['user'] = $nickname;
        header('Location: index.php');
        exit();
    } else {
        $error_message = "Toto uživatelské jméno neexistuje.";
    }
}

function getUserByNickname($nickname)
{
    global $conn;
    $stmt = $conn->prepare("SELECT nickname FROM users WHERE nickname = ?");
    $stmt->bind_param("s", $nickname);
    $stmt->execute();
    $stmt->bind_result($user);
    $stmt->fetch();
    $stmt->close();
    return $user;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { max-width: 400px; padding: 20px; background-color: rgba(255, 255, 255, 0.9); box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 8px; text-align: center; }
        input[type="text"], input[type="submit"], .back-button { width: calc(100% - 22px); padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ccc; }
        input[type="submit"] { background-color: #333; color: white; cursor: pointer; }
        input[type="submit"]:hover { background-color: #555; }
        .back-button { background-color: #999; color: white; text-decoration: none; display: inline-block; text-align: center; }
        .back-button:hover { background-color: #777; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Přihlášení</h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input type="text" name="nickname" placeholder="Zadejte vaši přezdívku" required>
            <input type="submit" value="Přihlásit">
        </form>
        <a href="index.php" class="back-button">Zpět</a>
    </div>
</body>
</html>
