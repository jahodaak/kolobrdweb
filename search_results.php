<?php
include 'common.php';
include 'header.php';

$query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';

$ads = searchAds($query);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V�sledky vyhled�v�n�</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; background-color: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .ad-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .ad { border: 1px solid #ddd; padding: 10px; background-color: #fff; }
        .ad img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
    <?php renderHeader(); ?>
    <div class="container">
        <h2>V�sledky vyhled�v�n� pro: "<?php echo $query; ?>"</h2>
        <div class="ad-grid">
            <?php if (empty($ads)): ?>
                <p>��dn� v�sledky.</p>
            <?php else: ?>
                <?php foreach ($ads as $ad): ?>
                    <div class="ad">
                        <h3><a href="ad.php?id=<?php echo htmlspecialchars($ad['id']); ?>"><?php echo htmlspecialchars($ad['name']); ?> (<?php echo htmlspecialchars($ad['manufacturer']); ?> - <?php echo htmlspecialchars($ad['model']); ?>)</a></h3>
                        <p>Cena: <?php echo htmlspecialchars($ad['price']); ?> Kc</p>
                        <p>Rychlost: <?php echo htmlspecialchars($ad['speed']); ?> km/h</p>
                        <p>V�ha: <?php echo htmlspecialchars($ad['weight']); ?> kg</p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
