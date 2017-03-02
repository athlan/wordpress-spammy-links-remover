<?php
/*
Plugin Name: Spammy Links Remover
Plugin URI: https://github.com/athlan/wordpress-spammy-links-remover
Description: Plugin browses all posts and finds external links and allow to easy remove them.
Author: Piotr Pelczar
Version: 1.0
Author URI: http://athlan.pl/
*/

add_action('admin_menu', function() {
    add_options_page(
        'Remove spammy links',
        'Remove spammy links',
        'administrator',
        'remove-spammy-links',
        require 'pages/remove-links/controller/remove-links.controller.php'
    );
});
