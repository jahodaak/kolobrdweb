<?php
include_once 'common.php';
include_once 'header.php';

if (!isset($_GET['id'])) {
    die("Inzerát nenalezen.");
}

$ad_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT ads.*, manufacturers.name AS manufacturer, models.model_name AS model FROM ads JOIN manufacturers ON ads.manufacturer_id = manufacturers.id JOIN models ON ads.model_id = models.id WHERE ads.id = ?");
$stmt->bind_param("i", $ad_id);
$stmt->execute();
$ad = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$ad) {
    die("Inzerát nenalezen.");
}

$images = [];
$stmt = $conn->prepare("SELECT image_path FROM ad_images WHERE ad_id = ?");
$stmt->bind_param("i", $ad_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $images[] = $row['image_path'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Inzerátu</title>
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
        }
        .ad-title {
            border: 2px solid #333;
            padding: 10px;
            margin-bottom: 20px;
        }
        .main-image {
            width: 100%;
            height: auto;
        }
        .thumbnails {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .thumbnails img {
            width: 100px;
            height: auto;
            cursor: pointer;
        }
        .details {
            margin-top: 20px;
        }
    </style>
    <script>
        function updateMainImage(src) {
            document.getElementById('main-image').src = src;
        }
    </script>
</head>
<body>
    
    <div class="container">
        <div class="ad-title">
            <h3><?php echo htmlspecialchars($ad['name']); ?> (<?php echo htmlspecialchars($ad['manufacturer']); ?> - <?php echo htmlspecialchars($ad['model']); ?>)</h3>
        </div>
        <div>
            <img id="main-image" src="<?php echo htmlspecialchars($images[0]); ?>" alt="Hlavní obrázek" class="main-image">
        </div>
        <div class="thumbnails">
            <?php foreach ($images as $image): ?>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="Miniatura" onclick="updateMainImage('<?php echo htmlspecialchars($image); ?>')">
            <?php endforeach; ?>
        </div>
        <div class="details">
            <p>Cena: <?php echo htmlspecialchars($ad['price']); ?> Kc</p>
            <p>Popis: <?php echo htmlspecialchars($ad['description']); ?></p>
            <p>Vykon: <?php echo htmlspecialchars($ad['performance']); ?> W</p>
            <p>Baterie: <?php echo htmlspecialchars($ad['battery']); ?> Wh</p>
            <p>Rychlost: <?php echo htmlspecialchars($ad['speed']); ?> km/h</p>
            <p>Vaha: <?php echo htmlspecialchars($ad['weight']); ?> kg</p>
            <p>Stav baterie: <?php echo htmlspecialchars($ad['battery_condition']); ?></p>
            <p>Poskozeni: <?php echo htmlspecialchars($ad['damage']); ?></p>
        </div>
    </div>
</body>
</html>
