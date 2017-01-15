$(function() {
    $(".cart-remove").on('click', function() {
        $(this).prev('input').val(0)
    });
});