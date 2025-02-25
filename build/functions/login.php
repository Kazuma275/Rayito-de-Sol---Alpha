<?php
session_start();

// Tiempo de vida de la sesión
$session_lifetime = 360;

// Expira la sesión si ha pasado el tiempo configurado
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_lifetime)) {
    session_unset();
    session_destroy();
    header("Location: /index.php?session_expired=true");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // Actualiza la última actividad

require_once __DIR__ . "/../../controllers/conection.php";  

// Manejo de autenticación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $error_message = '';

    if ($username && $password) {
        // Validación básica del nombre de usuario
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
            $error_message = "El nombre de usuario solo puede contener letras, números y guiones bajos.";
        } else {
            $sql = "SELECT user_id, username, password FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();

                    if (password_verify($password, $user['password'])) {
                        // Regenerar ID de sesión para evitar ataques de fijación de sesión
                        session_regenerate_id(true);

                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['logged_in'] = true;

                        // Asignar rol de admin si el usuario es 'sergio' o 'alvaro'
                        $_SESSION['role'] = ($username === 'sergio' || $username === 'alvaro') ? 'admin' : 'user';

                        header("Location: /index.php");
                        exit();
                    } else {
                        $error_message = "Contraseña incorrecta.";
                    }
                } else {
                    $error_message = "Usuario no encontrado.";
                }
                $stmt->close();
            } else {
                $error_message = "Error en la consulta: " . $conn->error;
            }
        }
    } else {
        $error_message = "Por favor, rellena todos los campos.";
    }
}

// Configuración de idioma
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'es';
$_SESSION['lang'] = preg_replace('/[^a-z]/', '', $lang);

// Ruta del archivo de idioma
$lang_file = $_SERVER['DOCUMENT_ROOT'] . "/lang/{$lang}.php";

// Verifica si el archivo de idioma existe
if (file_exists($lang_file)) {
    include $lang_file;
} else {
    die("Error: Archivo de idioma no encontrado.");
}

$conn->close(); // Cierra la conexión
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang['title']; ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/darkmode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.css">

    <!-- Favicon -->
    <link rel="icon" href="/img/favicon.png" type="image/x-icon">

    <!-- JS -->
    <script defer src="/js/javascript.js"></script>
    <script defer src="/js/darkmode.js"></script>
    <script defer src="/js/languague.js"></script>
    <script defer src="/js/eye.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Navbar -->
        <nav>
            <div class="topnav" id="myTopnav">
                <a href="/index.php#parallax-section" class="active">Home</a>
                <a href="/index.php#amenities"><?php echo $lang['amenities']?></a>
                <a href="/index.php#gallery"><?php echo $lang['gallery']?></a>
                <a href="/index.php#reviews"><?php echo $lang['reviews']?></a>
                <a href="/index.php#ubication"><?php echo $lang['ubication']; ?></a>
                <a href="/build/functions/login.php"><?php echo $lang['account']?></a>
                <?php if (isset($_SESSION['username']) && $_SESSION['logged_in'] === true): ?>
                    <a href="/build/crud/data/create.php"><?php echo $lang['make_reservation']?></a>
                    <a href="/build/functions/information.php" class="login-message"><?php echo "Hey," . htmlspecialchars($_SESSION['username']); ?></a>
                    <a href="/build/functions/contact.php"><?php echo $lang['contact_title']?></a>
                <?php endif; ?>

                <div class="settings-container" style="position: relative;">
                    <!-- Selector de idioma -->
                    <div class="language-selector">
                        <img id="current-flag" src="/img/idiomas/<?php echo $_SESSION['lang'] ?? 'es'; ?>.png" alt="<?php echo $lang['current_lang'] ?? 'Español'; ?>" class="flag">
                        <ul class="language-menu">
                            <?php foreach (['en', 'fr', 'es', 'cn', 'it', 'br', 'ua', 'ru'] as $code): ?>
                                <li><a href="?lang=<?php echo $code; ?>"><img src="/img/idiomas/<?php echo $code; ?>.png" alt="<?php echo ucfirst($code); ?>" class="flag-preview"></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Botón de Modo Oscuro/Claro -->
                    <label class="dayNight">
                        <input type="checkbox" id="darkmode-toggle">
                        <div></div>
                    </label>
                </div>
            </div>
        </nav>

        <?php if (isset($_GET['registration_success']) && $_GET['registration_success'] === 'true'): ?>
        <div class="success-message">
            ¡Registro realizado con éxito! Ahora puedes iniciar sesión.
        </div>
        <?php endif; ?>

        <!-- Sección signup -->
        <section id="signup">
            <h2><?php echo $lang['login']; ?></h2>
            <p><?php echo $lang['login_message']; ?></p>
            <form action="/build/functions/login.php" method="POST" class="login-form">
                <label for="username"><?php echo $lang['login_username']; ?></label>
                <input type="text" id="username" name="username" required>

                <label for="password"><?php echo $lang['login_password']; ?></label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required>
                    <i id="toggle-password" class="fa fa-eye"></i>
                </div>

                <input type="submit" value="<?php echo $lang['login']?>">
            </form>

            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="links">
                <?php echo $lang['register_prompt']; ?> <a href="/build/functions/signup.php"><?php echo $lang['register_link']; ?></a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-links">
                    <a href="/index.php#parallax-section"><?php echo $lang['home']; ?></a>
                    <a href="/index.php#amenities"><?php echo $lang['amenities']; ?></a>
                    <a href="/index.php#gallery"><?php echo $lang['gallery']; ?></a>
                    <a href="/index.php#reviews"><?php echo $lang['reviews']; ?></a>
                    <a href="/build/functions/login.php"><?php echo $lang['account']; ?></a>
                </div>

                <div class="social-media">
                    <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.twitter.com"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.whatsapp.com"><i class="fab fa-whatsapp"></i></a>
                </div>

                <div class="copyright">
                    &copy; 2024 <a href="https://rayitodesol.es"><?php echo $lang['site_name']; ?></a>. <?php echo $lang['rights']; ?>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
