jQuery(document).ready( function($) {
    var menu_item = $('.toplevel_page_dmcm_dashboard');
    menu_item.toggleClass('wp-not-current-submenu wp-has-current-submenu');
    menu_item.find('li a').each(function(){
        if ($(this).attr('href') === 'edit.php?post_type=dealers') {
            $(this).parent().addClass('current');
        }
    });
});
