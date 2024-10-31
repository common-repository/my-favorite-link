<?php

if (!defined('ABSPATH')) {
    exit;
}

class MyFavoriteLink
{
	private $wpdb;

	const PLUGIN_NAME = "my-favorite-links";

	public function __construct($wpdb)
	{
		$this->wpdb = $wpdb;
		$this->mfl_init_version_check();
	}

	private function mfl_init_version_check()
	{
		if (version_compare(phpversion(), '5.3.2', '<')) {
			add_action('admin_notices', array($this, 'mfl_old_php_error'));
		} else {
			add_action('init', 'my_favorite_link_translation');
			add_action('plugins_loaded', 'my_favorite_link_db_check');
			add_action('admin_init', array($this, 'my_favorite_link_enqueue'));
			add_action('admin_menu', array($this, 'my_favorite_link_menu_option'));
		}
	}

	function mfl_prepare_database()
	{
		$mfl_table_name = MFL_DB_TABLE;
		$mfl_table_name_cat = MFL_DB_TABLE_CAT;

		$charset_collate = $this->wpdb->get_charset_collate();

		$mfl_sql = "CREATE TABLE $mfl_table_name (
		  id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
		  name TINYTEXT NOT NULL,
		  site TEXT NOT NULL,
		  category TINYTEXT NOT NULL,
		  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (id)
		) $charset_collate";

		$mfl_sql_cat = "CREATE TABLE $mfl_table_name_cat (
		  id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
		  name TINYTEXT NOT NULL,
		  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (id)
		) $charset_collate";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta($mfl_sql);
		dbDelta($mfl_sql_cat);

		add_option('mfl_db_version', MFL_VERSION);
	}

	function mfl_old_php_error()
	{
		$message = sprintf(esc_html__('The %2$sMy Favorite Link%3$s plugin requires %2$sPHP 5.3.2+%3$s to run properly. Your current version of PHP is %2$s%1$s%3$s', 'my-favorite-link'), phpversion(), '<strong>', '</strong>', '<br/>');
		printf('<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post($message));
	}

	function my_favorite_link_db_check()
	{
		if (get_site_option('mfl_db_version') != MFL_VERSION) {
			$this->mfl_prepare_database();
		}
	}

	function my_favorite_link_translation()
	{
		load_plugin_textdomain('my-favorite-link', FALSE, MFL_TEXT_DOMAIN);
	}

	function my_favorite_link_enqueue()
	{
		wp_register_style('my_favorite_link_css', MFL_ASSETS . 'style.css');
		wp_enqueue_style('my_favorite_link_css');
		wp_register_script('my_favorite_link_js', MFL_ASSETS . 'script.js');
		wp_enqueue_script('my_favorite_link_js');
	}

	function my_favorite_link_menu_option()
	{
		add_menu_page('My Favorite Links', 'My Links', 'manage_options', 'my_favorite_link', array($this, 'my_favorite_link_page'), 'dashicons-star-filled', 4);
	}

	function my_favorite_link_page()
	{
		require_once MFL_PATH . 'class-action.php';
		require_once MFL_PATH . 'views/favorite-form.php';
		require_once MFL_PATH . 'views/favorite-list.php';
	}
}

$mfl = new MyFavoriteLink($wpdb);
register_activation_hook(MFL_URI, array($mfl, 'mfl_prepare_database'));
