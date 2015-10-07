<script type="text/javascript">
    /**
     * This class is in charge of adding a padding top and padding bottom to
     * the search bar through jQuery.
     */
    (function(){
        "use strict";
        var canvasHeight = $(window).height();
        var navHeight = $('.navbar.navbar-default').height();
        var footerHeight = $('.panel-footer').height();
        var container = $('.container').height();
        var pageHeading = $('.page-heading').height();
        var addHeight = canvasHeight - (navHeight + container);
        $(document).ready(function (){
            $('.container').css('padding-top', addHeight/4);
            $('.container.contentHeight').css('padding-bottom', addHeight/4);
        });
    })();
</script>
