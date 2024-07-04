<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přidat Inzerát</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header, nav { background-color: #333; color: white; padding: 10px 20px; text-align: center; }
        nav a { color: white; text-decoration: none; margin: 0 10px; }
        .container { max-width: 800px; margin: auto; padding: 20px; background-color: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <header>
        <h1>Přidat Elektrickou Koloběžku</h1>
    </header>
    <nav>
        <a href="index.php">Domů</a>
        <a href="sell.php">Prodat koloběžku</a>
        <a href="add.php">Přidat inzerát</a>
        <a href="forum.php">Fórum</a>
        <a href="chat.php">Chatovací místnosti</a>
        <a href="search.php">Vyhledávání</a>
        <a href="signup.php">Registrace</a>
    </nav>
    <div class="container">
        <form method="POST" action="add.php">
            <label for="name">Název Koloběžky:</label><br>
            <input type="text" id="name" name="name" required><br><br>
            <label for="price">Cena (Kč):</label><br>
            <input type="number" id="price" name="price" required><br><br>
            <label for="description">Popis:</label><br>
            <textarea id="description" name="description" required></textarea><br><br>
            <input type="submit" value="Přidat Koloběžku">
        </form>

        <?php
        function save_scooter($name, $price, $description)
        {
            $scooter = "$name|$price|$description\n";
            file_put_contents('scooters.txt', $scooter, FILE_APPEND);
            echo "<p>Koloběžka byla úspěšně přidána!</p>";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $price = htmlspecialchars($_POST['price']);
            $description = htmlspecialchars($_POST['description']);
            save_scooter($name, $price, $description);
        }
        ?>
    </div>
</body>
</html>
