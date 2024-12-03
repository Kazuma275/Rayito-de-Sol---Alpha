<?php

session_start();

$_SESSION["token"] = md5(time());

// Define un idioma predeterminado
$default_lang = 'es';

// Obtén el idioma de la URL o de la sesión
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang; // Guarda el idioma seleccionado en la sesión
} elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang']; // Usa el idioma almacenado en la sesión
} else {
    $lang = $default_lang; // Usa el idioma predeterminado
}

// Ruta del archivo de idioma
$lang_file = __DIR__ . "/lang/{$lang}.php";

// Verifica si el archivo existe
if (file_exists($lang_file)) {
    include $lang_file;
} else {
    die("Error: Archivo de idioma no encontrado.");
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php"); // Redirigir al login si no está autenticado
    exit();
}

?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang['manage_account']; ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">

    <!-- Favicon -->
    <link rel="icon" href="../img/favicon.png" type="image/x-icon">

    <!-- JS -->
    <script defer src="../js/javascript.js"></script>
    <script defer src="../js/darkmode.js"></script>
    <script defer src="../js/languague.js"></script>
</head>
<body>
    <div class="container">
        <!-- Navbar -->
        <nav>
            <div class="topnav" id="myTopnav">
                <a href="../index.php#parallax-section"><?php echo $lang['home']; ?></a>
                <a href="../index.php#amenities"><?php echo $lang['amenities']; ?></a>
                <a href="../index.php#gallery"><?php echo $lang['gallery']; ?></a>
                <a href="../index.php#reviews"><?php echo $lang['reviews']; ?></a>
                <a href="#"><?php echo $lang['account']; ?></a>
            </div>
        </nav>
        
        <!-- Sección de gestión de cuenta -->
        <section id="manage-account">
            <h2><?php echo $lang['manage_account']; ?></h2>
            <p><?php echo $lang['manage_message']; ?></p>

            <!-- Formulario para actualizar datos -->
            <form action="update_user.php" method="POST" class="manage-form">
                <label for="username"><?php echo $lang['update_username']; ?>:</label>
                <input type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>" required>
                
                <label for="email"><?php echo $lang['update_email']; ?>:</label>
                <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" required>

                <input type="submit" value="<?php echo $lang['update_info']; ?>">
            </form>

            <!-- Formulario para cambiar contraseña -->
            <form action="change_password.php" method="POST" class="password-form">
                <label for="current_password"><?php echo $lang['current_password']; ?>:</label>
                <input type="password" id="current_password" name="current_password" required>

                <label for="new_password"><?php echo $lang['new_password']; ?>:</label>
                <input type="password" id="new_password" name="new_password" required>

                <label for="confirm_password"><?php echo $lang['confirm_password']; ?>:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <input type="submit" value="<?php echo $lang['update_password']; ?>">
            </form>

            <!-- Enlace para cerrar sesión -->
            <a href="logout.php" class="logout-button"><?php echo $lang['logout']; ?></a>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-links">
                    <a href="../index.php#parallax-section"><?php echo $lang['home']; ?></a>
                    <a href="../index.php#amenities"><?php echo $lang['amenities']; ?></a>
                    <a href="../index.php#reviews"><?php echo $lang['reviews']; ?></a>
                    <a href="../index.php#gallery"><?php echo $lang['gallery']; ?></a>
                    <a href="#"><?php echo $lang['account']; ?></a>
                </div>
                <div class="social-media">
                    <a href="https://www.facebook.com" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.twitter.com" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.whatsapp.com" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
                <div class="copyright">
                    &copy; 2024 <a href="https://rayitodesol.es">Rayito de Sol</a>. <?php echo $lang['rights']; ?>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>