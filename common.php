<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "electric_scooters";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if (!function_exists('getUser')) {
    function getUser()
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return getUser() === 'jahodovydurex';
    }
}

if (!function_exists('getManufacturers')) {
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
}

if (!function_exists('getUserId')) {
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
}

if (!function_exists('renderHeader')) {
    function renderHeader()
    {
        ?>
        <header>
            <h1>Kolobìžková stanice</h1>
        </header>
        <nav>
            <a href="index.php">Domu</a>
            <a href="sell.php">Prodat kolobezku</a>
            <a href="ads.php">Seznam inzeratu</a>
            <a href="forum.php">Forum</a>
            <a href="chat.php">Chatovaci mistnosti</a>
            <?php if (getUser()): ?>
                <a href="profile.php">Muj Profil</a>
                <?php if (isAdmin()): ?>
                    <a href="admin_nastroje.php">Admin Nastroje</a>
                <?php endif; ?>
                <a href="logout.php" style="float:right;">Odhlasit</a>
            <?php else: ?>
                <div class="dropdown" style="float:right;">
                    <a class="dropbtn">Login/Register</a>
                    <div class="dropdown-content">
                        <a href="register.php">Registrace</a>
                        <a href="login.php">Prihlaseni</a>
                    </div>
                </div>
            <?php endif; ?>
        </nav>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                background-image: url('pozadi/pozadi1.PNG');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
            }
            header {
                background-color: #333;
                color: white;
                padding: 20px;
                text-align: center;
                position: relative;
            }
            h1 {
                margin: 0;
                padding: 0;
            }
            nav {
                background-color: rgba(68, 68, 68, 0.8);
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
            .dropdown {
                position: relative;
                display: inline-block;
            }
            .dropbtn {
                background-color: #444;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border: none;
                cursor: pointer;
            }
            .dropdown-content {
                display: none;
                position: absolute;
                background-color: #f9f9f9;
                min-width: 160px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                z-index: 1;
            }
            .dropdown-content a {
                color: black;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
            }
            .dropdown-content a:hover {
                background-color: #f1f1f1;
            }
            .dropdown:hover .dropdown-content {
                display: block;
            }
        </style>
        <?php
    }
}
?>
