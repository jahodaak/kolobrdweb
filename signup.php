<?php
include 'common.php';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrace</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 300px; margin: 100px auto; padding: 20px; background-color: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); text-align: center; }
        input[type="text"], input[type="submit"] { width: 100%; padding: 10px; margin-top: 10px; }
    </style>
</head>
<body>
    <?php renderHeader(); ?>
    <div class="container">
        <form action="register.php" method="POST">
            <input type="text" name="nickname" placeholder="Zadejte vaši přezdívku" required>
            <input type="submit" value="Registrovat">
        </form>
    </div>
</body>
</html>
