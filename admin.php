<?php
include 'common.php';
include 'header.php';

if (!isAdmin()) {
    header('Location: index.php');
    exit();
}

// Funkce pro získání statistik
function getTotalUsers()
{
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    return $total;
}

function getTotalAds()
{
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM ads");
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    return $total;
}

function getTotalTopics()
{
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM topics");
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    return $total;
}

function getTotalChatRooms()
{
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM chat_rooms");
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    return $total;
}

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Nástroje</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: auto; padding: 20px; background-color: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .stats { display: flex; justify-content: space-around; margin-bottom: 20px; }
        .stats div { text-align: center; }
    </style>
</head>
<body>
    <?php renderHeader(); ?>
    <div class="container">
        <h2>Admin Nástroje</h2>
        <div class="stats">
            <div>
                <h3>Celkový počet uživatelů</h3>
                <p><?php echo getTotalUsers(); ?></p>
            </div>
            <div>
                <h3>Celkový počet inzerátů</h3>
                <p><?php echo getTotalAds(); ?></p>
            </div>
            <div>
                <h3>Celkový počet témat</h3>
                <p><?php echo getTotalTopics(); ?></p>
            </div>
            <div>
                <h3>Celkový počet chatovacích místností</h3>
                <p><?php echo getTotalChatRooms(); ?></p>
            </div>
        </div>
        <div>
            <h3>Vytvořit nového uživatele</h3>
            <form method="POST" action="admin.php">
                <input type="text" name="nickname" placeholder="Přezdívka" required>
                <input type="submit" name="create_user" value="Vytvořit">
            </form>
        </div>
        <div>
            <h3>Seznam uživatelů</h3>
            <!-- Zde přidáme seznam uživatelů s možností je smazat nebo upravit -->
        </div>
        <div>
            <h3>Seznam inzerátů</h3>
            <!-- Zde přidáme seznam inzerátů s možností je smazat nebo upravit -->
        </div>
        <div>
            <h3>Seznam témat</h3>
            <!-- Zde přidáme seznam témat s možností je smazat nebo upravit -->
        </div>
        <div>
            <h3>Seznam chatovacích místností</h3>
            <!-- Zde přidáme seznam chatovacích místností s možností je smazat nebo upravit -->
        </div>
    </div>
</body>
</html>
