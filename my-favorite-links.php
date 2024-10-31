<?php

/**
 * Plugin Name: My Favorite Link
 * Plugin URI: https://wordpress.org/plugins/my-favorite-link
 * Description: Solo un Plugin de ayuda para el uso de WordPress.
 * Version: 1.0
 * Author: AQP hosting
 * Author URI: https://alojamientowp.org/
 * Text Domain: my-favorite-link
 * Domain Path: /languages
 **/

if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;

define('MFL_URI', __FILE__);
define('MFL_PATH', plugin_dir_path(__FILE__));
define('MFL_VERSION', '1.0');
define('MFL_ASSETS', plugin_dir_url(__FILE__) . 'assets/');
define('MFL_TEXT_DOMAIN', basename(dirname(__FILE__)) . '/languages/');
define('MFL_DB_TABLE', $wpdb->prefix . 'my_favorite_links');
define('MFL_DB_TABLE_CAT', $wpdb->prefix . 'my_favorite_links_cat');

require_once MFL_PATH . 'class-init.php';
