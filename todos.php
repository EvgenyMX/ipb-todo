<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://me.ru
 * @since             1.0.0
 * @package           Todos
 *
 * @wordpress-plugin
 * Plugin Name:       ToDos
 * Plugin URI:        https://me.ru
 * Description:       Интеграция с внешним API
 * Version:           1.0.0
 * Author:            Mikhalev Evgeniy
 * Author URI:        https://me.ru/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       todos
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TODOS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-todos-activator.php
 */
function activate_todos() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-todos-activator.php';
	Todos_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-todos-deactivator.php
 */
function deactivate_todos() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-todos-deactivator.php';
	Todos_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_todos' );
register_deactivation_hook( __FILE__, 'deactivate_todos' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-todos.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_todos() {

	$plugin = new Todos();
	$plugin->run();

}
run_todos();
