(function($){

    /* jquery sortable plugin for drag and drop prioritizer */
    function updateSortOrder() {
        var mappedItems = $(".js-sort-order tbody").children('tr').map(function(){

            return $(this).children('td[data-id=id]').attr('id');

        }).get().join(';');

        $('.current_order').val( mappedItems );
    }

    /**
     * Get all the categories from magento
     * @param data
     * @param cat_id
     * @param element
     */
    function dmcm_get_category_products (data, cat_id, element) {

        $('.icon-loader').remove();

        //Display current category parent and name
        var current_cat = element.html(),
            current_cat_parent = element.closest('ul').prev().html();

        if(current_cat == undefined){
            current_cat = '';
        }

        current_cat_parent = (current_cat_parent == undefined) ? '' :  ' - ' + current_cat_parent;

        $( 'h2' ).html('Category Items');

        $( 'h2' ).append( current_cat_parent + ' - ' + current_cat );

        var cat_string = '',
            cat_id_field = $('<input />');

        if ($('#js-cat-id').length > 0) {
            $('#js-cat-id').remove();
        }

        cat_id_field.attr('id', 'js-cat-id');
        cat_id_field.attr('type', 'hidden');
        cat_id_field.attr('name', 'category_id');
        cat_id_field.attr('value', cat_id);

        $('#dmcm--content-container').append(cat_id_field);

        /* Empty Category Items*/
        $('.sortable-container').empty();

        $.each(data, function(index, val) {
            if(index % 2 == 0) {

                /* iterate through array or object */
                $('.sortable-container').append('<tr><td data-id="id" id="' + val.id + '">' + val.id + '</td><td>' + val.name + '</td></tr>');

            } else {

                /* iterate through array or object */
                $('.sortable-container').append('<tr class="alternate"><td data-id="id" id="' + val.id + '">' + val.id + '</td><td>' + val.name + '</td></tr>');
            }
        });

        /* semi-colon seperated string of current item order */
        var mappedItems = $('tr').map(function(){
            return $(this).children('td[data-id=id]').attr('id');
        }).get().join(';');

        $('.current_order').val( mappedItems );
    }


    $(document).on('click', '.js-dmcm-get-cat-products', function(e) {
        e.preventDefault();

        $('.icon-loader').remove();

        $('<div class="icon-loader" style="width: 25px; height: 25px;"></div>').insertBefore('h2');
        //element clicked
        element = $( this );

        $('#dmcm-categories-tree').find('.active').removeClass('active');

        $('#dmcm_category_id').val(element.data('cat_id'));

        element.addClass('active');

        e.preventDefault();

        /*  Check if Sort Order has been changed */
        if( $('.sortable-container').html().trim() && $('.order_flag').val() == "true" ){

            var answer = confirm('Changes have been made to the Current Order. Please click "OK" to save changes.');

        }

        /* If Sort Order is not saved then save */
        if( answer ){

            $('.dmcm-submit').children('button').trigger('click');

            //Do Not Pass Go
            return false;

        } else {

            //Set flag back to false
            $('.order_flag').val('false');

        }

        var trigger = e.target || e.srcElement,
            endpoint = trigger.href,
            json = {};

        json.method = 'get_products_order';
        json.is_ajax = 1;

        for (var i in trigger.dataset) {
            json[i] = trigger.dataset[i];
        }

        $.ajax({
                url: endpoint,
                type: 'POST',
                dataType: 'json',
                data: json,
            })
            .done(function(data) {
                console.log("success", data);

                /* Fetch category products by id and name */
                dmcm_get_category_products(data, json.cat_id, element);

            })
            .fail(function(e) {
                console.log("error", e);
            })
            .always(function() {
                console.log("complete");
            });

        return false;

    });

    $(".sortable-container").disableSelection();

    $(".sortable-container").sortable({
        update: function(){
            updateSortOrder();
        }
    });

})(jQuery)