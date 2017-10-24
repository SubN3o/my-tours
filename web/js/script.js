// initialisation des select de Materialize

$(document).ready(function () {
  
    $('.modal').modal();
    // Script JS pour l'autocomplétion

    // hide #back-top first
    $("#back-top").hide();

    // fade in #back-top
    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').fadeOut();
            }
        });

        // scroll body to 0px on click
        $('#back-top a').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });

    $('#alert_close').click(function(){
        $( "#alert_box" ).fadeOut( "slow", function() {
        });
    });

    //ouverture du formulaire contact
    $('.button-collapse').sideNav({
            edge: 'right', // Choose the horizontal origin
            draggable: false, // Choose whether you can drag to open on touch screens
            closeOnClick: true
        }
    );

    $('select').material_select();
});

$(document).ready(function(){
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
});

