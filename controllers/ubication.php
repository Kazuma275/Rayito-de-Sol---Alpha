<?php
session_start();
include './language.php';
// Después de que se define el idioma
var_dump($_SESSION['lang']); // Esto debería mostrar solo un string como "es" o "en"

?>

<div id="iframe">
    <?php 
    $iframe_url = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d801.9384161512957!2d-4.718110630417203!3d36.48766537649933!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd731f8e245ede8f%3A0xa37947aa87978cdd!2sCl.%20Alhamar%2C%2010%2C%2029649%20Mijas%2C%20M%C3%A1laga!5e0!3m2!1es!2ses&hl=" . htmlspecialchars($_SESSION['lang']);
    echo '<iframe src="' . $iframe_url . '" width="100%" height="500px" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
    ?>
</div>




<!-- Cargar la API de Google Maps con la clave de API -->
<script src="https://maps.googleapis.com/maps/api/js?key=TU_CLAVE_API&callback=initMap" async defer></script>

<!-- Llamada al archivo JavaScript externo -->
<script src="js/ubication.js"></script>
