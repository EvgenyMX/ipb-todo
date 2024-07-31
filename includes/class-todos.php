<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://me.ru
 * @since      1.0.0
 *
 * @package    Todos
 * @subpackage Todos/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Todos
 * @subpackage Todos/includes
 * @author     Mikhalev Evgeniy <mixalev10a@gmail.com>
 */
class Todos {
	protected $loader;
	protected $plugin_name;
	protected $version;
	public function __construct() {
		if ( defined( 'TODOS_VERSION' ) ) {
			$this->version = TODOS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'todos';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-todos-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-todos-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-todos-admin.php';
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-todos-table.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-todos-public.php';

		$this->loader = new Todos_Loader();

	}
	private function set_locale() {

		$plugin_i18n = new Todos_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Todos_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );


		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_todo_page' );
		$this->loader->add_action( 'wp_ajax_sync-todo', $plugin_admin, 'sync_todo' );

		// $this->loader->add_action( 'wp_ajax_sync-todo', $plugin_admin, 'sync_todo' );

	}

	private function define_public_hooks() {

		$plugin_public = new Todos_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );




	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}
	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
