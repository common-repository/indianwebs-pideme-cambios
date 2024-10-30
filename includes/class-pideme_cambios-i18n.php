<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       jmedrano.dev
 * @since      1.0.0
 *
 * @package    Pideme_cambios
 * @subpackage Pideme_cambios/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pideme_cambios
 * @subpackage Pideme_cambios/includes
 * @author     Joan Medrano <joanmedranofoz@gmail.com>
 */
class Pideme_cambios_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pideme_cambios',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
