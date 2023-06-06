// Ocultar y mostrar sidebar
(function($) {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('show');
    });

})(jQuery);

// Mostrar el spinner al cargar la página
document.getElementById('loader-container').style.display = 'block';

// Ocultar el spinner una vez que se haya cargado la página
window.addEventListener('load', function() {
    document.getElementById('loader-container').style.display = 'none';
});

// Mostrar el spinner al enviar el formulario con la clase 'form'
var forms = document.getElementsByClassName('form');
for (var i = 0; i < forms.length; i++) {
    forms[i].addEventListener('submit', function() {
        document.getElementById('loader-container').style.display = 'block';
    });
}

window.toMin = function(input) {
    input.value = input.value.toLowerCase();
}

window.toMay = function(input) {
    input.value = input.value.toUpperCase();
}

window.toCap = function(input) {
    const conectores = ["del", "de", "la", "y", "el", "los", "las"];
    let palabras = input.value.toLowerCase().split(" ");

    for (let i = 0; i < palabras.length; i++) {
        if (conectores.indexOf(palabras[i]) === -1 || i === 0) {
            palabras[i] = palabras[i].charAt(0).toUpperCase() + palabras[i].slice(1);
        }
    }

    input.value = palabras.join(" ");
}

window.toNum = function(input) {
    const value = input.value;

    // Permite solo dos números usando una expresión regular
    const numeros = value.match(/\d{0,2}/);

    if (numeros) {
        input.value = numeros[0];
    } else {
        input.value = "";
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
