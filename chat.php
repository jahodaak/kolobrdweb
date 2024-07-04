<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'common.php';

// Fetch chat rooms with active user count
$rooms = [];
$result = $conn->query("SELECT chat_rooms.*, (SELECT COUNT(*) FROM messages WHERE messages.room_id = chat_rooms.id) AS active_users FROM chat_rooms ORDER BY name ASC");
while ($room = $result->fetch_assoc()) {
    $rooms[] = $room;
}

// Add new chat room
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_room'])) {
    $name = htmlspecialchars($_POST['name']);
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

    $stmt = $conn->prepare("INSERT INTO chat_rooms (name, created_by, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $user_id, $image);
    if ($stmt->execute()) {
        $stmt->close();
        header('Location: chat.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Delete chat room
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_room'])) {
    $room_id = intval($_POST['room_id']);
    $user = getUser();
    $user_id = getUserId($user);

    $stmt = $conn->prepare("DELETE FROM chat_rooms WHERE id = ? AND created_by = ?");
    $stmt->bind_param("ii", $room_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: chat.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatovací Místnosti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(245, 245, 245, 0.9);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .section {
            border: 2px solid #aaa;
            padding: 10px;
            background-color: rgba(245, 245, 245, 0.8);
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .section h2 {
            text-align: center;
            background-color: #bbb;
            padding: 10px;
            border-radius: 8px;
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 10px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"], input[type="file"], button {
            margin-bottom: 10px;
        }
        .chat-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .chat-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.8);
            transition: transform 0.3s;
            flex: 1 1 calc(50% - 20px);
        }
        .chat-item:hover {
            transform: scale(1.05);
        }
        .chat-item a {
            text-decoration: none;
            color: #333;
        }
        .chat-item img {
            max-width: 50px;
            max-height: 50px;
            margin-left: 10px;
        }
        .active-users {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }
        .active-users span {
            margin-left: 5px;
        }
        .green-dot {
            width: 10px;
            height: 10px;
            background-color: green;
            border-radius: 50%;
            display: inline-block;
        }
        button {
            font-size: 1.2em;
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
        .chat-content {
            border: 2px solid #aaa;
            padding: 10px;
            background-color: rgba(245, 245, 245, 0.8);
            border-radius: 8px;
            min-height: 200px;
        }
    </style>
</head>
<body>
   
    <div class="container">
        <div class="section">
            <h2>Vytvořit Novou Místnost</h2>
            <?php if (getUser()): ?>
                <form method="POST" action="chat.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Název místnosti:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Zvolit obrázek místnosti:</label>
                        <input type="file" id="image" name="image">
                    </div>
                    <button type="submit" name="new_room">Přidat místnost</button>
                </form>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <h2>Existující Místnosti</h2>
            <div class="chat-list">
                <?php foreach ($rooms as $room): ?>
                    <div class="chat-item">
                        <div>
                            <a href="chat.php?room_id=<?php echo $room['id']; ?>" class="room-link"><?php echo htmlspecialchars($room['name']); ?></a>
                            <?php if ($room['image']): ?>
                                <img src="<?php echo htmlspecialchars($room['image']); ?>" alt="Obrázek místnosti">
                            <?php endif; ?>
                        </div>
                        <div class="active-users">
                            <?php if ($room['active_users'] > 0): ?>
                                <div class="green-dot"></div>
                            <?php endif; ?>
                            <span><?php echo $room['active_users']; ?> aktivních uživatelů</span>
                        </div>
                        <?php if (getUserId(getUser()) == $room['created_by']): ?>
                            <form method="POST" action="chat.php" style="display:inline;">
                                <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['id']); ?>">
                                <button type="submit" name="delete_room">Smazat</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="section">
            <h2>Vybraná Místnost</h2>
            <div class="chat-content" id="chat-content">
                <?php
                if (isset($_GET['room_id'])) {
                    $room_id = intval($_GET['room_id']);
                    $stmt = $conn->prepare("SELECT messages.message, messages.created_at, users.nickname FROM messages JOIN users ON messages.user_id = users.id WHERE messages.room_id = ? ORDER BY messages.created_at ASC");
                    $stmt->bind_param("i", $room_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($message = $result->fetch_assoc()) {
                        echo '<div><strong>' . htmlspecialchars($message['nickname']) . ':</strong> ' . htmlspecialchars($message['message']) . '<br><span style="font-size:small;color:gray;">' . htmlspecialchars($message['created_at']) . '</span></div>';
                    }
                    $stmt->close();
                }
                ?>
            </div>
            <div id="chat-form" style="display:<?php echo isset($_GET['room_id']) ? 'block' : 'none'; ?>;">
                <form method="POST" action="send_message.php">
                    <input type="hidden" name="room_id" value="<?php echo isset($_GET['room_id']) ? intval($_GET['room_id']) : ''; ?>">
                    <textarea name="message" required></textarea><br>
                    <button type="submit">Odeslat zprávu</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.room-link').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                fetch(this.href)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('chat-content').innerHTML = data;
                        document.getElementById('chat-form').style.display = 'block';
                    });
            });
        });
    </script>
</body>
</html>
