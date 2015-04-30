(function ($) {
    $(document).ready(function () {
        $('.truncated').hide()
            .after('<i class="icon-plus-sign glyphicon glyphicon-plus-sign" aria-hidden="true"></i>')
            .next().on('click', function () {
                $(this).toggleClass('icon-minus-sign glyphicon glyphicon-minus-sign').prev().toggle();
            });
    });
})(jQuery);
