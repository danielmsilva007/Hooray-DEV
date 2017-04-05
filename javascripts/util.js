
$(document).ready(function() {

    $('.produto-galeria-thumb .thumbnail').click(function() {
        $('.produto-galeria-thumb .thumbnail').removeClass('current');
        $(this).addClass('current');
        var path = $(this).find('img').attr('src');
        $('.produto-galeria-imagem img').attr('src', path);
    });


    $('#mais-vendidos-carousel').carousel({
      interval: false
    });

    
    $('.filtro-botao').click(function(e) {
        e.preventDefault();
        $("#filtro-conteudo").toggle();
    });

    $("#filtro-conteudo").height($(".vitrine-produtos").height());


    $('#slide-nav.navbar-inverse').after($('<div class="inverse" id="navbar-height-col"></div>'));
  
    $('#slide-nav.navbar-default').after($('<div id="navbar-height-col"></div>'));  

    var toggler = '.navbar-toggle';
    var pagewrapper = '#page-content';
    var navigationwrapper = '.navbar-header';
    var menuwidth = '100%'; // the menu inside the slide menu itself
    var slidewidth = '80%';
    var menuneg = '-100%';
    var slideneg = '-80%';

    $(window).on("resize", function () {

        if ($(window).width() > 992 && $('.navbar-toggle').is(':hidden')) {
            $(selected).removeClass('slide-active');
        }


    });

    $("#slide-nav").on("click", toggler, function (e) {

        var selected = $(this).hasClass('slide-active');

        $('#slidemenu').stop().animate({
            left: selected ? menuneg : '0px'
        });

        $('#navbar-height-col').stop().animate({
            left: selected ? slideneg : '0px'
        });

        $(pagewrapper).stop().animate({
            left: selected ? '0px' : slidewidth
        });

        $(navigationwrapper).stop().animate({
            left: selected ? '0px' : slidewidth
        });


        $(this).toggleClass('slide-active', !selected);
        $('#slidemenu').toggleClass('slide-active');


        $('#page-content, .navbar, body, .navbar-header').toggleClass('slide-active');


    });


    var selected = '#slidemenu, #page-content, body, .navbar, .navbar-header';


});
