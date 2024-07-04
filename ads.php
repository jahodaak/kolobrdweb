<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'common.php';

$ads = [];
$stmt = $conn->prepare("SELECT ads.*, manufacturers.name AS manufacturer, models.model_name AS model, ad_images.image_path 
                        FROM ads 
                        JOIN manufacturers ON ads.manufacturer_id = manufacturers.id 
                        JOIN models ON ads.model_id = models.id
                        LEFT JOIN ad_images ON ads.id = ad_images.ad_id
                        ORDER BY ads.id DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($ad = $result->fetch_assoc()) {
    $ads[] = $ad;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seznam Inzerátů</title>
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
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ad-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .ad {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .ad img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        a {
            text-decoration: none;
            color: #333;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php renderHeader(); ?>
    <div class="container">
        <h2>Seznam Inzerátů</h2>
        <div class="ad-grid">
            <?php if (empty($ads)): ?>
                <p>Žádné inzeráty.</p>
            <?php else: ?>
                <?php foreach ($ads as $ad): ?>
                    <div class="ad">
                        <a href="ad.php?id=<?php echo htmlspecialchars($ad['id']); ?>">
                            <h3><?php echo htmlspecialchars($ad['name']); ?> (<?php echo htmlspecialchars($ad['manufacturer']); ?> - <?php echo htmlspecialchars($ad['model']); ?>)</h3>
                            <p>Cena: <?php echo htmlspecialchars($ad['price']); ?> Kč</p>
                            <?php if ($ad['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($ad['image_path']); ?>" alt="Foto koloběžky">
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
