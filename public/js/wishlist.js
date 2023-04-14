(function ($) {

    $('.remove-item').on('click', function (e) {

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



    function add_wishlist(wishlist, product_id) {
        $.ajax({
            url: "/wishlist",
            method: 'post',
            data: {
                product_id: product_id,
                _token: csrf_token
            },
            success: function (response) {
                console.log(response);
                if (response.status === 'added') {
                    wishlist.addClass('active');
                    alert('product added wishlist')
                } else if (response.status === 'removed') {
                    wishlist.removeClass('active');
                    alert('product deleted from wishlist')
                }

            }
        });
    }

    $('.wishlist button:button').on('click', function (e) {
        var $wishlist = $(this).closest('.wishlist'); // Cache the wishlist element
        add_wishlist($wishlist, $(this).data('id'))

    });


    $('.wishlist a').on('click', function (e) {
        var $wishlist = $(this).closest('.wishlist'); // Cache the wishlist element
        add_wishlist($wishlist, $(this).data('id'))

    });



})(jQuery);
