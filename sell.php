<?php
include_once 'common.php';
include_once 'header.php';

if (!getUser()) {
    header('Location: signup.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $price = htmlspecialchars($_POST['price']);
    $description = htmlspecialchars($_POST['description']);
    $manufacturer_id = htmlspecialchars($_POST['manufacturer']);
    $model_id = htmlspecialchars($_POST['model']);
    $performance = htmlspecialchars($_POST['performance']);
    $battery = htmlspecialchars($_POST['battery']);
    $speed = htmlspecialchars($_POST['speed']);
    $weight = htmlspecialchars($_POST['weight']);
    $battery_condition = htmlspecialchars($_POST['battery_condition']);
    $damage = htmlspecialchars($_POST['damage']);
    $images = [];

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            $image = 'uploads/' . basename($_FILES['images']['name'][$key]);
            move_uploaded_file($tmp_name, $image);
            $images[] = $image;
        }
    }

    $user = getUser();
    $user_id = getUserId($user);

    $stmt = $conn->prepare("INSERT INTO ads (user_id, name, price, description, manufacturer_id, model_id, performance, battery, speed, weight, battery_condition, damage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssiisiiis", $user_id, $name, $price, $description, $manufacturer_id, $model_id, $performance, $battery, $speed, $weight, $battery_condition, $damage);
    if ($stmt->execute()) {
        $ad_id = $stmt->insert_id;
        $stmt->close();

        foreach ($images as $image) {
            $stmt = $conn->prepare("INSERT INTO ad_images (ad_id, image_path) VALUES (?, ?)");
            $stmt->bind_param("is", $ad_id, $image);
            $stmt->execute();
            $stmt->close();
        }

        header("Location: ad.php?id=$ad_id");
        exit();
    } else {
        echo "Chyba: " . $stmt->error;
    }
}

function getManufacturers()
{
    global $conn;
    $stmt = $conn->prepare("SELECT id, name FROM manufacturers");
    $stmt->execute();
    $result = $stmt->get_result();
    $manufacturers = [];
    while ($row = $result->fetch_assoc()) {
        $manufacturers[] = $row;
    }
    $stmt->close();
    return $manufacturers;
}

function getUserId($nickname)
{
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE nickname = ?");
    $stmt->bind_param("s", $nickname);
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();
    return $id;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prodat Kolobezku</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: auto; padding: 20px; background-color: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .form-group { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .form-group label { flex: 1; margin-right: 10px; }
        .form-group input, .form-group select, .form-group textarea { flex: 3; }
        input[type="submit"] { background-color: #333; color: white; border: none; padding: 10px 20px; cursor: pointer; }
        input[type="submit"]:hover { background-color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="sell.php" enctype="multipart/form-data">
            <label for="name">Nazev Kolobezky:</label><br>
            <input type="text" id="name" name="name" required><br><br>
            <label for="price">Cena (Kc):</label><br>
            <input type="number" id="price" name="price" required><br><br>
            <label for="description">Popis:</label><br>
            <textarea id="description" name="description" required></textarea><br><br>
            <div class="form-group">
                <label for="manufacturer">Vyrobce:</label>
                <select id="manufacturer" name="manufacturer" required onchange="updateModels()">
                    <option value="">Vyberte vyrobce</option>
                    <?php
                    $manufacturers = getManufacturers();
                    foreach ($manufacturers as $manufacturer) {
                        echo "<option value=\"{$manufacturer['id']}\">{$manufacturer['name']}</option>";
                    }
                    ?>
                </select>
                <label for="model">Model:</label>
                <select id="model" name="model" required>
                    <option value="">Vyberte model</option>
                </select>
            </div><br><br>
            <label for="performance">Vykon (W):</label><br>
            <input type="number" id="performance" name="performance" required><br><br>
            <label for="battery">Baterie (Wh):</label><br>
            <input type="number" id="battery" name="battery" required><br><br>
            <label for="speed">Rychlost (km/h):</label><br>
            <input type="number" id="speed" name="speed" required><br><br>
            <label for="weight">Vaha (kg):</label><br>
            <input type="number" id="weight" name="weight" required><br><br>
            <label for="battery_condition">Stav baterie:</label><br>
            <input type="text" id="battery_condition" name="battery_condition" required><br><br>
            <label for="damage">Poskozeni:</label><br>
            <input type="text" id="damage" name="damage" required><br><br>
            <label for="images">Fotky:</label><br>
            <input type="file" id="images" name="images[]" multiple><br><br>
            <input type="submit" value="Pridat Kolobezku">
        </form>
    </div>
    <script>
        async function updateModels() {
            const manufacturerId = document.getElementById('manufacturer').value;
            const response = await fetch(`get_models.php?manufacturer_id=${manufacturerId}`);
            const models = await response.json();
            const modelSelect = document.getElementById('model');
            modelSelect.innerHTML = '<option value="">Vyberte model</option>';
            models.forEach(model => {
                const option = document.createElement('option');
                option.value = model.id;
                option.text = model.model_name;
                modelSelect.add(option);
            });
        }
    </script>
</body>
</html>
