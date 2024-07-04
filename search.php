<?php
include 'common.php';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vyhledávání</title>
</head>
<body>
    <?php renderHeader(); ?>
    <div class="container">
        <h2>Vyhledávání</h2>
        <form method="GET" action="search.php">
            <input type="text" name="query" placeholder="Hledat..." required>
            <input type="submit" value="Vyhledat">
        </form>
        <!-- Zde by bylo zobrazení výsledků vyhledávání -->
    </div>
</body>
</html>
