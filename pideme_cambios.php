<?php

/**
 * @link              https://indianwebs.com
 * @since             1.0.0
 * @package           IndianWebs Pídeme Canbios
 *
 * @wordpress-plugin
 * Plugin Name:       IndianWebs Pídeme Cambios
 * Plugin URI:        http://indianwebs.com/plugins
 * Description:       A WordPress plugin to help you in the tedious work of taking note of the changes in a website.
 * Version:           1.0.0
 * Author:            Joan Medrano
 * Author URI:        http://lawebdelpoble.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       indianwebs-pideme-cambios
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
define( 'PIDEME_CAMBIOS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pideme_cambios-activator.php
 */
function activate_pideme_cambios() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pideme_cambios-activator.php';
	Pideme_cambios_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pideme_cambios-deactivator.php
 */
function deactivate_pideme_cambios() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pideme_cambios-deactivator.php';
	Pideme_cambios_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pideme_cambios' );
register_deactivation_hook( __FILE__, 'deactivate_pideme_cambios' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pideme_cambios.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pideme_cambios() {

	$plugin = new Pideme_cambios();
	$plugin->run();

}
run_pideme_cambios();
