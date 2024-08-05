/*
Author       : Code-Theme
Template Name: Find Houses - HTML5 Template
Version      : 1.0
*/

"use strict";

jQuery(document).on('ready', function ($) {

   
    /*---------------------------------
     //------ ANIMATE HEADER ------//
     ----------------------------------*/
    $(window).on('scroll', function () {
        var sticky = $(".sticky-header");
        var scroll = $(window).scrollTop();
        if (scroll < 265) {
            sticky.removeClass("sticky");
        } else {
            sticky.addClass("sticky");
        }
    });

    
    /*----------------------------------
    //------ OWL CAROUSEL ------//
    -----------------------------------*/
    $('.style1').owlCarousel({
        loop: true,
        margin: 10,
        autoplay: true,
        autoplayTimeout: 5000,
        responsive: {
            0: {
                items: 1
            },
            400: {
                items: 1,
                margin: 20
            },
            500: {
                items: 1,
                margin: 20
            },
            768: {
                items: 1,
                margin: 20
            },
            991: {
                items: 2,
                margin: 20
            },
            1025: {
                items: 2,
                margin: 20
            }
        }
    });
 $('.style-3').owlCarousel({
        loop: true,
        margin: 10,
        autoplay: true,
        autoplayTimeout: 5000,
        responsive: {
            0: {
                items: 1
            },
            400: {
                items: 1,
                margin: 20
            },
            500: {
                items: 1,
                margin: 20
            },
            768: {
                items: 1,
                margin: 20
            },
            991: {
                items: 1,
                margin: 20
            },
            1025: {
                items: 1,
                margin: 20
            }
        }
    });
   

    /*----------------------------------
    //------ JQUERY SCROOLTOP ------//
    -----------------------------------*/
    var go = $(".go-up");
    $(window).on('scroll', function () {
        var scrolltop = $(this).scrollTop();
        if (scrolltop >= 50) {
            go.fadeIn();
        } else {
            go.fadeOut();
        }
    });

    /*----------------------------------
    //----- JQUERY COUNTER UP -----//
    -----------------------------------*/
    $('.counter').counterUp({
        delay: 10,
        time: 5000,
        offset: 100,
        beginAt: 0,
        formatter: function (n) {
            return n.replace(/,/g, '.');
        }
    });

   
    /*----------------------------------------------
    //------ FILTER TOGGLE (ON GOOGLE MAPS) ------//
    ----------------------------------------------*/
    $('.filter-toggle').on('click', function () {
        $(this).parent().find('form').stop(true, true).slideToggle();
    });

   
    /*----------------------------------
    //------ MODAL ------//
    -----------------------------------*/
    var modal = {};
    modal.hide = function () {
        $('.modal').fadeOut();
        $("html, body").removeClass("hid-body");
    };
    $('.modal-open').on("click", function (e) {
        e.preventDefault();
        $('.modal').fadeIn();
        $("html, body").addClass("hid-body");
    });
    $('.close-reg').on("click", function () {
        modal.hide();
    });
    
    /*----------------------------------
    //------ TABS ------//
    -----------------------------------*/
    $(".tabs-menu a").on("click", function (a) {
        a.preventDefault();
        $(this).parent().addClass("current");
        $(this).parent().siblings().removeClass("current");
        var b = $(this).attr("href");
        $(".tab-contents").not(b).css("display", "none");
        $(b).fadeIn();
    });

}(jQuery));
