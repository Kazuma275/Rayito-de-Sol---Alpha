document.addEventListener('DOMContentLoaded', () => {
    const toggleCheckbox = document.querySelector('.dayNight input');
    const topNav = document.querySelector('.topnav'); // Selecciona la navbar
    const amenities = document.querySelector('#amenities'); // Amenidades
    const gallery = document.querySelector('#gallery'); // Contenedor de galería
    const reviewsContainer = document.querySelector('#reviews'); // Contenedor de reviews
    const ubication = document.querySelector('#ubication'); // Ubicación
    const footer = document.querySelector('.footer'); // Footer

    // Función para habilitar modo oscuro
    const enableDarkMode = () => {
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'enabled');

        topNav.style.backgroundColor = '#000000'; 
        topNav.style.color = '#f2f2f2'; // Texto blanco

        amenities.style.backgroundColor = '#000000';
        amenities.style.color = '#dddddd'; // Texto claro
        
        gallery.style.backgroundColor = '#101316';
        gallery.style.color = '#dddddd'; // Texto claro

        reviewsContainer.style.backgroundColor = '#20252D';
        reviewsContainer.style.color = '#dddddd'; // Texto claro

        ubication.style.backgroundColor = '#2F3843';
        ubication.style.color = '#dddddd'; // Texto claro

        footer.style.backgroundColor = '#3F4B5A';
        footer.style.color = '#dddddd'; // Texto claro
    };

    // Función para deshabilitar modo oscuro
    const disableDarkMode = () => {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'disabled');

        topNav.style.backgroundColor = '#9EBBE0'; // Fondo azul
        topNav.style.color = '#f2f2f2'; // Texto blanco

        amenities.style.backgroundColor = '#D8E4F3'; // Fondo claro
        amenities.style.color = '#000000'; // Texto oscuro
        
        gallery.style.backgroundColor = '#E2EBF6'; // Fondo claro
        gallery.style.color = '#000000'; // Texto oscuro

        reviewsContainer.style.backgroundColor = '#ECF1F9'; // Fondo claro
        reviewsContainer.style.color = '#000000'; // Texto oscuro

        ubication.style.backgroundColor = '#F5F8FC'; // Fondo claro
        ubication.style.color = '#000000'; // Texto oscuro

        footer.style.backgroundColor = '#9EBBE0'; // Fondo claro
        footer.style.color = '#000000'; // Texto oscuro
    };

    // Verifica el estado al cargar la página
    if (localStorage.getItem('darkMode') === 'enabled') {
        enableDarkMode();
        toggleCheckbox.checked = true; // Asegura que el toggle esté marcado
    } else {
        disableDarkMode();
        toggleCheckbox.checked = false; // Asegura que el toggle no esté marcado
    }

    // Evento para cambiar el estado del modo oscuro
    toggleCheckbox.addEventListener('change', () => {
        if (toggleCheckbox.checked) {
            enableDarkMode();
        } else {
            disableDarkMode();
        }
    });
});
