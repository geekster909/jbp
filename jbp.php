<?php
/**
 * Plugin Name: JBP
 * Description: JB Portal
 * Author: Justin Bond
 * Author URI: http://justin-bond.com
 */
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once __DIR__ . '/jbp/config.php';
require_once __DIR__ . '/jbp/jbp.php';

function jbp_run() {
    if (is_admin()) {
        $app = new Jbp();
    }
}

add_action('init', 'jbp_run');

require_once __DIR__ . '/jbp/lib/functions.php';
