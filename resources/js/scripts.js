(function($) {

    "use strict";

    var fullHeight = function() {

        $('.js-fullheight').css('height', $(window).height());
        $(window).resize(function(){
            $('.js-fullheight').css('height', $(window).height());
        });

    };
    fullHeight();

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('show');
    });

})(jQuery);

$(document).ready(function() {
    // Mostrar la pantalla de carga al inicio
    $('#loader-container').fadeIn();
});

// Ocultar la pantalla de carga cuando el contenido esté listo
$(window).on('load', function() {
    setTimeout(function() {
        $('#loader-container').fadeOut();
    }); // Desvanecerse después de medio segundo (500 ms)
});
