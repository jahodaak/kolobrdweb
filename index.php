<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'common.php';

// Načíst inzeráty
$ads = [];
$stmt = $conn->prepare("SELECT ads.*, manufacturers.name AS manufacturer, models.model_name AS model, ad_images.image_path 
                        FROM ads 
                        JOIN manufacturers ON ads.manufacturer_id = manufacturers.id 
                        JOIN models ON ads.model_id = models.id
                        LEFT JOIN ad_images ON ads.id = ad_images.ad_id
                        ORDER BY ads.id DESC LIMIT 8");
$stmt->execute();
$result = $stmt->get_result();
while ($ad = $result->fetch_assoc()) {
    $ads[] = $ad;
}
$stmt->close();

// Načíst top 5 témat fóra
$topics = [];
$stmt = $conn->prepare("SELECT topics.*, users.nickname FROM topics 
                        JOIN users ON topics.user_id = users.id 
                        ORDER BY comment_count DESC LIMIT 5");
$stmt->execute();
$result = $stmt->get_result();
while ($topic = $result->fetch_assoc()) {
    $topics[] = $topic;
}
$stmt->close();

// Načíst top 5 chatovacích místností podle počtu uživatelů
$chat_rooms = [];
$stmt = $conn->prepare("SELECT chat_rooms.*, users.nickname FROM chat_rooms 
                        JOIN users ON chat_rooms.created_by = users.id 
                        ORDER BY (SELECT COUNT(*) FROM messages WHERE messages.room_id = chat_rooms.id) DESC LIMIT 5");
$stmt->execute();
$result = $stmt->get_result();
while ($chat_room = $result->fetch_assoc()) {
    $chat_rooms[] = $chat_room;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
      <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hlavní stránka</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Váš CSS kód */
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hlavní stránka</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://source.unsplash.com/random/1920x1080?city') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .ad, .topic, .chat-room {
            border: 2px solid #aaa;
            padding: 10px;
            background-color: rgba(245, 245, 245, 0.8);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        .ad img, .topic img, .chat-room img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .ad:hover, .topic:hover, .chat-room:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            background-color: #bbb;
            padding: 10px;
            border-radius: 8px;
        }
        .header-container {
            background-color: #bbb;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .grid-controls {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }
        .grid-controls select {
            padding: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <?php renderHeader(); ?>
    </div>
    <div class="container">
        <h2>Nejpopulárnější konverzace</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($topics as $topic): ?>
                <div class="topic" onclick="window.location.href='topic.php?id=<?php echo htmlspecialchars($topic['id']); ?>'">
                    <h3><?php echo htmlspecialchars($topic['title']); ?></h3>
                    <p>Autor: <?php echo htmlspecialchars($topic['nickname']); ?></p>
                    <?php if ($topic['image']): ?>
                        <img src="<?php echo htmlspecialchars($topic['image']); ?>" alt="Obrázek tématu">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Nejpopulárnější chatovací místnosti</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($chat_rooms as $chat_room): ?>
                <div class="chat-room" onclick="window.location.href='chat_room.php?id=<?php echo htmlspecialchars($chat_room['id']); ?>'">
                    <h3><?php echo htmlspecialchars($chat_room['name']); ?></h3>
                    <p>Autor: <?php echo htmlspecialchars($chat_room['nickname']); ?></p>
                    <?php if ($chat_room['image']): ?>
                        <img src="<?php echo htmlspecialchars($chat_room['image']); ?>" alt="Obrázek místnosti">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Nejnovejší Inzeráty</h2>
        <div class="grid-controls">
            <label for="grid-size">Velikost inzerátů:</label>
            <select id="grid-size" onchange="updateGridSize()">
                <option value="small">Malé</option>
                <option value="medium" selected>Střední</option>
                <option value="large">Velké</option>
            </select>
        </div>
        <div id="ad-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (empty($ads)): ?>
                <p>Žádné inzeráty.</p>
            <?php else: ?>
                <?php foreach ($ads as $ad): ?>
                    <div class="ad" onclick="window.location.href='ad.php?id=<?php echo htmlspecialchars($ad['id']); ?>'">
                        <h3><?php echo htmlspecialchars($ad['name']); ?> (<?php echo htmlspecialchars($ad['manufacturer']); ?> - <?php echo htmlspecialchars($ad['model']); ?>)</h3>
                        <p>Cena: <?php echo htmlspecialchars($ad['price']); ?> Kč</p>
                        <?php if ($ad['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($ad['image_path']); ?>" alt="Foto koloběžky">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function updateGridSize() {
            const gridSize = document.getElementById('grid-size').value;
            const adGrid = document.getElementById('ad-grid');
            if (gridSize === 'small') {
                adGrid.classList.remove('grid-cols-2', 'grid-cols-3', 'grid-cols-4');
                adGrid.classList.add('grid-cols-1');
            } else if (gridSize === 'medium') {
                adGrid.classList.remove('grid-cols-1', 'grid-cols-3', 'grid-cols-4');
                adGrid.classList.add('grid-cols-2');
            } else if (gridSize === 'large') {
                adGrid.classList.remove('grid-cols-1', 'grid-cols-2', 'grid-cols-4');
                adGrid.classList.add('grid-cols-3');
            }
        }
    </script>
</body>
</html>
