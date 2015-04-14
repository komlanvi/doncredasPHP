/**
 * Created by doncredas on 04/04/15.
 */
jQuery(function ($) {
    $("#main").css({
        fontSize: 17 + "px",
        lineHeight: 1.6,
        backgroundColor: "#FFF"
    });

    //Set the carousel options
    $('#quote-carousel').carousel({
        pause: true,
        interval: 4000
    });
});