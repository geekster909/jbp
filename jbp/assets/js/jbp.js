(function($){

    $.fn.goTo = function(location) {
        var position = (typeof location !== "undefined") ? 0 : $(this).offset().top - 50;
        $('html, body').animate({
            scrollTop: position + 'px'
        }, 'slow');
        return this;
    };

    $(document).on('click', '.js-open-form', function(e){
        e.preventDefault();
        $('#js-import').toggleClass('is-visible is-hidden');
    });

    var obj = {};

    function togglePanels(trigger) {
        var panel_to_hide = $('#jbp-' + obj.hide);
        var panel_to_show = $('#jbp-' + obj.show);

        panel_to_hide.addClass('is-hidden').bind('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function() {
            $(trigger).parents('ul').find('li.active').removeClass('active');

            panel_to_hide.toggleClass('is-hidden hidden');
            panel_to_show.removeClass('hidden');

            $(trigger).parent().addClass('active');

            updateUrl(obj.hide, obj.show);

            obj = {};
            panel_to_hide.unbind();
        });
    }

    function updateUrl(hide, show) {
        var params = window.location.search.replace('panel=' + hide, 'panel=' + show);
        var pageUrl = window.location.pathname + params;

        window.history.replaceState(null, null, pageUrl);
    }

    $(document).on('click', '.js-jbp-panel-nav-link', function(e) {
        e.preventDefault();

        if ($(this).parent().hasClass('active')) {
            return false;
        }

        obj.show = $(this).parent().data('panel');
        obj.hide = $(this).parents('ul').find('li.active').data('panel');

        togglePanels(e.target || e.srcElement);
    });

    $(document).on('click', '.js-jbp-remove-image', function(e){
        e.preventDefault();

        var data = $(this).data();
        var name = data.name;
        var image_name = data.path;
        var position = data.position;
        var input_value = document.createElement("input");
        var input = $(this).siblings('input[type=hidden]');

        input.val('');

        input_value.name = "delete_" + name + "[" + position + "]";
        input_value.type = "hidden";

        input_value.value = image_name;

        $(this).siblings('img').addClass('to-remove');

        $(this).next().find('input[type=file]').removeAttr('disabled').on('change', function(){
            input.val($(this).val().toLowerCase().split('fakepath\\')[1]);
        });

        $(this).parent().append(input_value);
    });


    $(document).on('change', '.js-jbp-select-image', function(){
        var value = $(this).val().split('fakepath\\')[1];
        $(this).siblings('input[type=hidden]').val(value);
    });


    function jbpRelatedProductsCreateTableRow(id, sku, status, name) {
        var tr = document.createElement('tr'),
            a = document.createElement('a'),
            remove = document.createElement('a'),
            td_id = document.createElement('td'),
            td_name = document.createElement('td'),
            td_sku = document.createElement('td'),
            td_status = document.createElement('td'),
            td_remove = document.createElement('td');

        tr.setAttribute('data-id', id);
        td_id.innerHTML = id;
        td_sku.innerHTML = sku;
        td_status.innerHTML = status;

        a.href = 'admin.php?page=jbp_products&action=edit&jbp_id=' + id;
        a.text = name;

        remove.href = '#';
        remove.text = '[x]';
        remove.classList.add('jbp-related-products-remove-row');
        remove.dataset.pid = id;

        td_name.appendChild(a);
        td_remove.appendChild(remove);

        tr.appendChild(td_id);
        tr.appendChild(td_name);
        tr.appendChild(td_sku);
        tr.appendChild(td_status);
        tr.appendChild(td_remove);

        return tr;
    }


    /**
     * [description]
     * @param  {[type]} e){                     e.preventDefault();        var trigger [description]
     * @return {[type]}      [description]
     */
    $(document).on('click', '.jbp-related-product-link', function(e){
        e.preventDefault();
        var trigger = e.target || e.scrElement,
            input = document.querySelector('#jbp-related-products-field'),
            table = document.querySelector('#jbp-related-products-table'),
            id, sku, name, status;

        if (trigger.classList.contains('disabled')) {
            return false;
        }

        id = trigger.dataset.pid;
        sku = trigger.dataset.sku;
        status = trigger.dataset.active;
        name = trigger.innerHTML;

        trigger.classList.add('disabled');
        input.value += ';' + id;

        $(table).find('tbody').append(jbpRelatedProductsCreateTableRow(id, sku, status, name));

        return true;
    });


    /**
     * [description]
     * @param  {[type]} e){                     e.preventDefault();        var trigger [description]
     * @return {[type]}      [description]
     */
    $(document).on('click', '.jbp-related-products-remove-row', function(e){
        e.preventDefault();

        var trigger = e.target || e.scrElement,
            input = document.querySelector('#jbp-related-products-field'),
            row = trigger.parentNode.parentNode,
            id = trigger.dataset.pid,
            selected = input.value.split(';'),
            index = selected.indexOf(id);

        selected.splice(index, 1);

        input.value = selected.toString().replace(new RegExp(',', 'g'), ';');
        row.remove();

        $('a[data-pid="'+ id +'"').removeClass('disabled');

        return true;
    });


    function updateRelatedOrder () {
        var mappedItems = jQuery(".js-related-sortable-container").children('tr').map(function(){

            return jQuery(this).attr('data-id');

        }).get().join(';');

        jQuery('#jbp-related-products-field').val( mappedItems );
    }

    jQuery(".js-related-sortable-container").sortable({
        update: function(){
            updateRelatedOrder();
        }
    });

    $('#js-csv-import-file-trigger').on('click', function(e){
        e.preventDefault();
        $('#js-csv-import-file-input').click();
    });


    $('#js-csv-import-file-input').on('change', function(e) {
        e.preventDefault();
        $("#js-csv-import-file-name").text(this.value.split('fakepath\\')[1]);
    });


    $('.js-jbp-importer-type').on('click', function(e) {
        e.preventDefault();

        var base_export_href = 'admin.php?page=jbp_importer&action=export';
        var type;
        var export_all_href_params;
        var export_template_href_params;

        if ($(this).hasClass('active')) {
            return;
        }

        if ($(this).siblings('a').hasClass('active')) {
            $(this).siblings('a').removeClass('active');
        } else {
            $('#js-import, #js-export').toggleClass('hidden is-hidden');

            setTimeout(function() {
                $('#js-import, #js-export').removeClass('is-hidden');
            }, 0);
        }

        type = $(this).data('type');

        $(this).addClass('active');

        $('#js-csv-import-file-type').attr('value', type);
        $('.js-export-all').attr('data-type', type);
        $('.js-export-template').attr('data-type', type);
        $('.js-type').text("(" + type + ")");

        export_all_href_params = $('.js-export-all').attr('href', base_export_href + '&type=all&module=' + type);
        export_template_href_params = $('.js-export-template').attr('href', base_export_href + '&type=template&module=' + type);
    });


    $(document).on('click', '.js-get-features', function(e) {
        var linkId = $(this).data('link');
        var spinner = $(this).siblings('.spinner');
        var row = $(this).parents('tr');

        row.addClass('active-row');
        spinner.toggleClass('is-active');

        $('.js-ajax-features').empty();

        e.preventDefault();

        $.post(ajaxurl, {
                action:'exec',
                model: 'vehicle',
                method: 'getProductFeatures',
                params: {
                    link_id: linkId,
                    toHtml: 1
                }
            },
            function(data) {
                spinner.toggleClass('is-active');
                $('.js-ajax-features').append(data).goTo();
            });
    });

    $(document).on('click', '#js-cancel-features', function(e){
        e.preventDefault();
        $('.js-ajax-features').empty();
        $('.active-row').goTo('top').removeClass('active-row');
    });

    $(document).on('click', '#js-save-features', function(e){
        e.preventDefault();

        var fields = $('.js-feature-field');
        var spinner = $(this).siblings('.spinner');
        var features = {};
        var current;
        var name;
        var feature_id;
        var value;

        spinner.toggleClass('is-active');
        $('.js-data').css({opacity: '.5'});

        for ( var i = 0; i < fields.length; i++ ) {
            current = $(fields[i]);

            name = current.attr('name').replace('features[', '').replace(']', '');
            value = current.val();

            if (name === 'feature_id') {
                feature_id = value;
                continue;
            }

            features[name] = value;
        }

        $.post(ajaxurl, {
            action:'exec',
            model: 'vehicle',
            method: 'saveFeatures',
            params: {
                feature_id: feature_id,
                data: features
            }
        }, function(data) {
            spinner.toggleClass('is-active');
            data = $.parseJSON(data);
            if (data.status === 'success') {
                $('.js-ajax-features').empty().text('Features Saved Successfully.');
                $('.active-row').goTo().removeClass('active-row').addClass('updated-row');
            }
        });
    });

    var confirmation = false;

    $(document).on('click', '.js-remove-pairing', function(e) {
        e.preventDefault();

        var spinner = $(this).siblings('.spinner');
        var trigger = $(this);
        var parent = trigger.parents('tr');

        spinner.toggleClass('is-active');

        if ( !confirmation ) {

            if (!confirm('Are you sure you want to delete this application? This action can\'t be undone.')) {
                spinner.toggleClass('is-active');
                return false;
            }

            confirmation = true;
        }

        var link_id = $(this).data('link');

        $.post(ajaxurl, {
            action:'exec',
            model: 'vehicle',
            method: 'removePairing',
            params: {
                link_id: link_id
            }
        }, function(data){
            parent.fadeOut(400, function(){ parent.remove(); });
        });
    });

    function updatePairingsTable( all_pairings, new_pairings ) {

        var current;
        var tr;
        var product_id;
        var product_name;
        var id_td;
        var name_td;
        var features_td;
        var remove_td;
        var features_link;
        var remove_link;
        var spinner = $('<span class="spinner"></span>');
        var table = $('#jbp-pairings-table');

        for (var i = 0; i < new_pairings.length; i++) {
            current = new_pairings[i];

            for (var p in all_pairings) {
                if (all_pairings[p].id == current.product_id) {
                    product_id = all_pairings[p].id;
                    product_name = all_pairings[p].name;
                    break;
                }
            }

            tr = $('<tr />');
            id_td = $('<td />');
            name_td = $('<td />');
            features_td = $('<td />');
            remove_td = $('<td />');
            features_link = $('<a />');
            remove_link = $('<a />');

            id_td.text( product_id );
            name_td.text( product_name );

            features_link.attr('href', '#');
            features_link.addClass('js-get-features');
            features_link.attr('data-link', current.link_id);
            features_link.text('[view]');
            features_td.append(features_link);
            features_td.append(spinner);

            remove_link.attr('href', '#');
            remove_link.addClass('js-remove-pairing');
            remove_link.attr('data-link', current.link_id);
            remove_link.text('[x]');
            remove_td.append(remove_link);
            remove_td.append(spinner);

            tr.addClass('new-row');
            tr.append(id_td);
            tr.append(name_td);
            tr.append(features_td);
            tr.append(remove_td);

            table.append(tr);
        }

        table.goTo('top');
    }

    $('#js-add-pairings').on('click', function(e){
        e.preventDefault();

        var products = $('.js-products:checked').toArray();
        var vehicle_id = $('#js-vehicle-id').val();
        var ids = [];
        var pairings = [];

        for (var i = 0; i < products.length; i++) {
            ids[i] = $(products[i]).val();
            pairings[i] = { id: $(products[i]).val(), name: $(products[i]).siblings('label').text() };
        }

        $.post(ajaxurl, {
            action: 'exec',
            model: 'vehicle',
            method: 'addPairings',
            params: {
                products: ids,
                vehicle_id: vehicle_id
            }
        }, function(data) {
            data = JSON.parse(data);
            updatePairingsTable(pairings, data.pairings);
        });
    });
    
    $('#js-remove-vehicle').on('click', function(e) {

        e.preventDefault();

        var vehicle_id = $(this).data('id');

        if (!confirm('This operation can\'t be undone. Do you want to continue?')) {
            return false;
        }

        console.log('removing vehicle id: ' + vehicle_id );

        $.post(ajaxurl, {
            action: 'exec',
            model: 'vehicle',
            method: 'removeVehicle',
            params: {
                vehicle_id: vehicle_id
            }
        }, function(data) {
            data = JSON.parse(data);
            if (data.hasOwnProperty('redirect')) {
                window.location.href = data.redirect;
            }
        });

    });
    
})(jQuery);