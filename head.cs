<? php
include 'common.php';
include 'header.php';
?>     ;

< !DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hlavní stránka</title>
    <style>
        body {
            font-family: Arial, sans-serif; /* Nastavení výchozího fontu pro celý dokument */
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            background-color: #444;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ad {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .ad img {
            max-width: 200px;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .form-group label {
            flex: 1;
            margin-right: 10px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            flex: 3;
        }
        input[type="submit"] {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #555;
        }
        .message {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .message p {
            margin: 0;
        }
        .message span {
            font-size: small;
            color: gray;
        }
        .container form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php renderHeader(); ?>
    <div class="container">
        <h2>Nejnovější Inzeráty</h2>
        <?php if (empty($ads)): ?>
            <p>Žádné inzeráty.</p>
        <?php else: ?>
            <?php foreach ($ads as $ad): ?>
                <div class="ad">
                    <h3><?php echo htmlspecialchars($ad['name']); ?> (<?php echo htmlspecialchars($ad['manufacturer']); ?> - <?php echo htmlspecialchars($ad['model']); ?>)</h3>
                    <p>Cena: <?php echo htmlspecialchars($ad['price']); ?> Kč</p>
                    <p>Popis: <?php echo htmlspecialchars($ad['description']); ?></p>
                    <p>Výkon: <?php echo htmlspecialchars($ad['performance']); ?> W</p>
                    <p>Baterie: <?php echo htmlspecialchars($ad['battery']); ?> Wh</p>
                    <p>Rychlost: <?php echo htmlspecialchars($ad['speed']); ?> km/h</p>
                    <p>Váha: <?php echo htmlspecialchars($ad['weight']); ?> kg</p>
                    <p>Stav baterie: <?php echo htmlspecialchars($ad['battery_condition']); ?></p>
                    <p>Poškození: <?php echo htmlspecialchars($ad['damage']); ?></p>
                    <?php if ($ad['image']): ?>
                        <p><img src="<?php echo htmlspecialchars($ad['image']); ?>" alt="Foto koloběžky"></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hlavní stránka</title>
    <style>
        body {
            font-family: Arial, sans-serif; /* Nastavení výchozího fontu pro celý dokument */
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            background-color: #444;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ad {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .ad img {
            max-width: 200px;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .form-group label {
            flex: 1;
            margin-right: 10px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            flex: 3;
        }
        input[type="submit"] {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #555;
        }
        .message {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .message p {
            margin: 0;
        }
        .message span {
            font-size: small;
            color: gray;
        }
        .container form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php renderHeader(); ?>
    <div class="container">
        <h2>Nejnovější Inzeráty</h2>
        <?php if (empty($ads)): ?>
            <p>Žádné inzeráty.</p>
        <?php else: ?>
            <?php foreach ($ads as $ad): ?>
                <div class="ad">
                    <h3><?php echo htmlspecialchars($ad['name']); ?> (<?php echo htmlspecialchars($ad['manufacturer']); ?> - <?php echo htmlspecialchars($ad['model']); ?>)</h3>
                    <p>Cena: <?php echo htmlspecialchars($ad['price']); ?> Kč</p>
                    <p>Popis: <?php echo htmlspecialchars($ad['description']); ?></p>
                    <p>Výkon: <?php echo htmlspecialchars($ad['performance']); ?> W</p>
                    <p>Baterie: <?php echo htmlspecialchars($ad['battery']); ?> Wh</p>
                    <p>Rychlost: <?php echo htmlspecialchars($ad['speed']); ?> km/h</p>
                    <p>Váha: <?php echo htmlspecialchars($ad['weight']); ?> kg</p>
                    <p>Stav baterie: <?php echo htmlspecialchars($ad['battery_condition']); ?></p>
                    <p>Poškození: <?php echo htmlspecialchars($ad['damage']); ?></p>
                    <?php if ($ad['image']): ?>
                        <p><img src="<?php echo htmlspecialchars($ad['image']); ?>" alt="Foto koloběžky"></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
