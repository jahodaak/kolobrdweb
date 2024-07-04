<?php
include 'common.php';

// Funkce pro kontrolu, zda m� u�ivatel profilov� obr�zek
function maProfilovyObrazek($userId)
{
    return file_exists("uploads/profiles/$userId.jpg");
}

// Maz�n� m�stnost� nebo p��sp�vk�
if (isset($_GET['smazat_id'])) {
    $smazat_id = intval($_GET['smazat_id']);
    $typ_smazani = $_GET['typ_smazani'];

    if ($typ_smazani == 'mistnost') {
        $stmt = $conn->prepare("DELETE FROM chat_rooms WHERE id = ?");
    } elseif ($typ_smazani == 'prispevek') {
        $stmt = $conn->prepare("DELETE FROM topics WHERE id = ?");
    } elseif ($typ_smazani == 'uzivatel') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    } elseif ($typ_smazani == 'inzerat') {
        $stmt = $conn->prepare("DELETE FROM ads WHERE id = ?");
    }

    $stmt->bind_param("i", $smazat_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_nastroje.php");
    exit();
}

// P�ejmenov�n� u�ivatele
if (isset($_POST['prejmenovat_uzivatele'])) {
    $user_id = intval($_POST['user_id']);
    $novy_nickname = htmlspecialchars($_POST['novy_nickname']);

    $stmt = $conn->prepare("UPDATE users SET nickname = ? WHERE id = ?");
    $stmt->bind_param("si", $novy_nickname, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_nastroje.php");
    exit();
}

// Z�sk�n� u�ivatel�
$uzivatele = [];
$result = $conn->query("SELECT id, nickname FROM users");
while ($row = $result->fetch_assoc()) {
    $uzivatele[] = $row;
}
$result->close();

// Z�sk�n� m�stnost�
$mistnosti = [];
$result = $conn->query("SELECT id, name FROM chat_rooms");
while ($row = $result->fetch_assoc()) {
    $mistnosti[] = $row;
}
$result->close();

// Z�sk�n� p��sp�vk�
$prispevky = [];
$result = $conn->query("SELECT id, title FROM topics");
while ($row = $result->fetch_assoc()) {
    $prispevky[] = $row;
}
$result->close();

// Z�sk�n� inzer�t�
$inzeraty = [];
$result = $conn->query("SELECT id, name FROM ads");
while ($row = $result->fetch_assoc()) {
    $inzeraty[] = $row;
}
$result->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin N�stroje</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .section {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fafafa;
        }
        .section h2 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button, .link-button {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: transform 0.3s, opacity 0.3s;
            text-decoration: none;
            display: inline-block;
            margin: 5px 0;
        }
        button:hover, .link-button:hover {
            transform: scale(1.05);
        }
        button:active, .link-button:active {
            opacity: 0.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="section">
            <h2>Seznam u�ivatel�</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>P�ezd�vka</th>
                        <th>Profilov� obr�zek</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($uzivatele as $uzivatel): ?>
                    <tr>
                        <td><?php echo $uzivatel['id']; ?></td>
                        <td><?php echo htmlspecialchars($uzivatel['nickname']); ?></td>
                        <td><?php echo maProfilovyObrazek($uzivatel['id']) ? 'Ano' : 'Ne'; ?></td>
                        <td>
                            <form method="POST" action="admin_nastroje.php" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $uzivatel['id']; ?>">
                                <input type="text" name="novy_nickname" placeholder="Nov� p�ezd�vka" required>
                                <button type="submit" name="prejmenovat_uzivatele">P�ejmenovat</button>
                            </form>
                            <a class="link-button" href="admin_nastroje.php?smazat_id=<?php echo $uzivatel['id']; ?>&typ_smazani=uzivatel">Smazat</a>
                            <a class="link-button" href="profile.php?id=<?php echo $uzivatel['id']; ?>">P�ej�t na profil</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h2>Seznam chatovac�ch m�stnost�</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N�zev</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mistnosti as $mistnost): ?>
                    <tr>
                        <td><?php echo $mistnost['id']; ?></td>
                        <td><?php echo htmlspecialchars($mistnost['name']); ?></td>
                        <td>
                            <a class="link-button" href="admin_nastroje.php?smazat_id=<?php echo $mistnost['id']; ?>&typ_smazani=mistnost">Smazat</a>
                            <a class="link-button" href="chat_room.php?id=<?php echo $mistnost['id']; ?>">P�ej�t do m�stnosti</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h2>Seznam p��sp�vk�</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N�zev</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prispevky as $prispevek): ?>
                    <tr>
                        <td><?php echo $prispevek['id']; ?></td>
                        <td><?php echo htmlspecialchars($prispevek['title']); ?></td>
                        <td>
                            <a class="link-button" href="admin_nastroje.php?smazat_id=<?php echo $prispevek['id']; ?>&typ_smazani=prispevek">Smazat</a>
                            <a class="link-button" href="topic.php?id=<?php echo $prispevek['id']; ?>">P�ej�t na p��sp�vek</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h2>Seznam inzer�t�</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N�zev</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inzeraty as $inzerat): ?>
                    <tr>
                        <td><?php echo $inzerat['id']; ?></td>
                        <td><?php echo htmlspecialchars($inzerat['name']); ?></td>
                        <td>
                            <a class="link-button" href="admin_nastroje.php?smazat_id=<?php echo $inzerat['id']; ?>&typ_smazani=inzerat">Smazat</a>
                            <a class="link-button" href="ad.php?id=<?php echo $inzerat['id']; ?>">P�ej�t na inzer�t</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
