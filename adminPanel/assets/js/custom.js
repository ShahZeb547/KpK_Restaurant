(function ($) {
    "use strict";
    var mainApp = {

        main_fun: function () {
            // Sidebar Menu
            $('#main-menu').metisMenu();

            // Responsive collapse
            $(window).bind("load resize", function () {
                if ($(this).width() < 768) {
                    $('div.sidebar-collapse').addClass('collapse');
                } else {
                    $('div.sidebar-collapse').removeClass('collapse');
                }
            });

            // Morris charts removed — safe for DataTables and modals
        },

        initialization: function () {
            mainApp.main_fun();
        }

    }

    // Initialize
    $(document).ready(function () {
        mainApp.main_fun();
    });

}(jQuery));
