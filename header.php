<!-- header.php -->

<header>
    <h1>Kolobìžková stanice</h1>
</header>
<nav>
    <a href="index.php">Domu</a>
    <a href="sell.php">Prodat kolobezku</a>
    <a href="ads.php">Seznam inzeratu</a>
    <a href="forum.php">Forum</a>
    <a href="chat.php">Chatovaci mistnosti</a>
    <a href="profile.php">Muj Profil</a>
    <?php if (isAdmin()): ?>
        <a href="admin.php">Admin Nástroje</a>
    <?php endif; ?>
    <?php if (getUser()): ?>
        <a href="logout.php" style="float:right;">Odhlasit</a>
    <?php else: ?>
        <div class="dropdown" style="float:right; position:relative;">
            <button class="dropbtn">Pøihlásit/Registrace</button>
            <div class="dropdown-content" style="position:absolute; right:0;">
                <a href="login.php">Pøihlásit</a>
                <a href="register.php">Registrace</a>
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
    .dropdown {
        display: inline-block;
    }
    .dropbtn {
        background-color: rgba(68, 68, 68, 0.8);
        color: white;
        padding: 10px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }
    .dropdown-content {
        display: none;
        background-color: white;
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
        background-color: #ddd;
    }
    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>
