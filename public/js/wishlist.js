(function($) {

    $('.remove-item').on('click', function(e) {

        let id = $(this).data('id');
        $.ajax({
            url: "/wishlist/" + id, //data-id
            method: 'delete',
            data: {
                _token: csrf_token
            },
            success: response => {
                $(`#${id}`).remove();
            }
        });
    });

    $('.add-wishlist').on('click', function(e) {
        $.ajax({
            url: "/wishlist",
            method: 'post',
            data: {
                product_id: $(this).data('id'),
                _token: csrf_token
            },
            success: response => {
                alert('product added wishlist')
            }
        });
    });

})(jQuery);
