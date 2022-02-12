require([
        "jquery"
    ],
    function($) {
        "use strict";
        $(document).ready(function() {
            $(".category-item-icon").click(function() {
                if ($(this).parent().siblings().css("display") == 'none') {
                    $(this).parent().siblings().css("display", "block");
                    $(this).css("transform", "rotate(180deg)").css("top", "40%");
                    $(this).parent().addClass('active');
                } else {
                    $(this).parent().siblings().css("display", "none");
                    $(this).css("transform", "rotate(0)");
                    $(this).parent().removeClass('active');
                }
            });
        });
    });
