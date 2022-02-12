require([
        "jquery",
        "owlcarousel"
    ],
    function($) {
        "use strict";
        $(document).ready(function() {
            $('.owl-carousel').owlCarousel({
                loop: false,
                margin: 30,
                nav: true,
                navContainer: '#customNav',
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    }
                },
                dots: false,
            })
        });

    });
