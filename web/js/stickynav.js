// Fixed Nav
jQuery(document).ready(function ($) {
    $(window).scroll(function(){

        var scrollTop = 0;

        if (document.getElementById("intro")){
            scrollTop = document.getElementById("intro").offsetHeight
        }

        if($(window).scrollTop() >= scrollTop){
            $('nav').css({
                position : 'fixed',
                top : '0'
            });
            document.getElementById('h1Home').style.marginTop = '140px';
        }
        if($(window).scrollTop() <= scrollTop){
            $('nav').removeAttr('style');
            document.getElementById('h1Home').style.marginTop = '70px';
        }
    })
});
