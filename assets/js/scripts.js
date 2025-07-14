/*
tinymce.init({
  selector: '#editor',
  language: 'es_MX',
  branding: false,
  plugins: 'autosave', autosave_interval: '50s', autosave_restore_when_empty: true,
  plugins: 'emoticons, fullscreen, image, link, media, lists advlist, searchreplace, save, preview, table, wordcount',
  toolbar1: 'undo redo | styles forecolor | bold italic | alignleft aligncenter alignright | image | media | link | emoticons | table | numlist | bullist | restoredraft | wordcount | searchreplace | save | fullscreen | preview ',
});
*/

document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.querySelector('form[action="<?php echo BASE_URL; ?>controllers/users/process_register.php"]');

    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            let isValid = true;

            // Validar username
            if (usernameInput.value.trim() === '') {
                alert('Por favor, introduce un nombre de usuario.');
                isValid = false;
            } else if (!isValidUsername(usernameInput.value.trim())) {
                alert('Por favor, introduce un nombre de usuario válido (solo letras, números y guiones bajos).');
                isValid = false;
            }

            // Validar email
            if (emailInput.value.trim() === '') {
                alert('Por favor, introduce un correo electrónico.');
                isValid = false;
            } else if (!isValidEmail(emailInput.value.trim())) {
                alert('Por favor, introduce un correo electrónico válido.');
                isValid = false;
            }

            // Validar password
            if (passwordInput.value.trim() === '') {
                alert('Por favor, introduce una contraseña.');
                isValid = false;
            } else if (passwordInput.value.length < 8) {
                alert('La contraseña debe tener al menos 8 caracteres.');
                isValid = false;
            }

            if (isValid) {
                registerForm.submit();
            }
        });
    }

    const loginForm = document.querySelector('form[action="<?php echo CONTROLLERS_URL?>users/process_login.php "]');

    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            let isValid = true;

            // Validar email
            if (emailInput.value.trim() === '') {
                alert('Por favor, introduce un correo electrónico.');
                isValid = false;
            } else if (!isValidEmail(emailInput.value.trim())) {
                alert('Por favor, introduce un correo electrónico válido.');
                isValid = false;
            }

            // Validar password
            if (passwordInput.value.trim() === '') {
                alert('Por favor, introduce una contraseña.');
                isValid = false;
            }

            if (isValid) {
                loginForm.submit();
            }
        });
    }

    const createArticleForm = document.querySelector('form[action="<?php echo CONTROLLERS_URL; ?>articles/process_articles.php"]');

    if (createArticleForm) {
        createArticleForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const titleInput = document.getElementById('title');
            const articleEditor = tinymce.get('editor');
            const categorySelect = document.getElementById('select_category');

            let isValid = true;

            // Validar title
            if (titleInput.value.trim() === '') {
                alert('Por favor, introduce un título.');
                isValid = false;
            } else if (!isValidTitle(titleInput.value.trim())) {
                alert('Por favor, introduce un título válido (mínimo 5 caracteres, solo letras, números, espacios y algunos caracteres especiales).');
                isValid = false;
            }

            // Validar article (TinyMCE)
            if (articleEditor.getContent().trim() === '') {
                alert('Por favor, introduce el contenido del artículo.');
                isValid = false;
            }

            // Validar category
            if (categorySelect.value === '') {
                alert('Por favor, selecciona una categoría.');
                isValid = false;
            }

            if (isValid) {
                createArticleForm.submit();
            }
        });
    }

    function isValidTitle(title) {
        const titleRegex = /^[a-zA-Z0-9\s.,?!]{5,}$/;
        return titleRegex.test(title);
    }

    const agregarCategoriaForm = document.querySelector('#agregarCategoriaModal form');

    if (agregarCategoriaForm) {
        agregarCategoriaForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const nombreInput = document.getElementById('nombre');

            let isValid = true;

            // Validar nombre
            if (nombreInput.value.trim() === '') {
                alert('Por favor, introduce un nombre para la categoría.');
                isValid = false;
            }

            if (isValid) {
                agregarCategoriaForm.submit();
            }
        });
    }

    const subirLibroForm = document.querySelector('#subirLibroModal form');

    if (subirLibroForm) {
        subirLibroForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const tituloInput = document.getElementById('titulo');
            const autorInput = document.getElementById('autor');

            let isValid = true;

            // Validar titulo
            if (tituloInput.value.trim() === '') {
                alert('Por favor, introduce un título para el libro.');
                isValid = false;
            }

            // Validar autor
            if (autorInput.value.trim() === '') {
                alert('Por favor, introduce un autor para el libro.');
                isValid = false;
            }

            if (isValid) {
                subirLibroForm.submit();
            }
        });
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidUsername(username) {
        const usernameRegex = /^[a-zA-Z0-9_]+$/;
        return usernameRegex.test(username);
    }

    function isValidTitle(title) {
        const titleRegex = /^[a-zA-Z0-9\s.,?!]{5,}$/;
        return titleRegex.test(title);
    }
});
