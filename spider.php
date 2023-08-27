<?php
/**
 * Grabr
 *
 * @package           Spider
 * @author            Aminul Islam
 * @copyright         2023 Aminul Islam
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Spider
 * Plugin URI:        https://aminul.net/wordpress-plugins/spider
 * Description:       Get latest feed from search engine like Google, Bing, WordPress, etc.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Aminul Islam
 * Author URI:        https://aminul.net
 * Text Domain:       spider
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://aminul.net/wordpress-plugins/spider
 */

require 'vendor/autoload.php';

// Define constants.
define( 'SPIDER_VERSION', '1.0.0' );
define( 'SPIDER_URL', plugin_dir_url( __FILE__ ) );
define( 'SPIDER_PATH', plugin_dir_path( __FILE__ ) );

function spider() {
	return \AminulBD\Spider\WordPress\WordPress::init([
		'version' => SPIDER_VERSION,
	]);
}

// Kick
spider();

// Load admin things.
if ( is_admin() ) {
    new \AminulBD\Spider\WordPress\Admin();
}


// // Function to register our new routes from the controller.
// function prefix_register_my_rest_routes() {
//     $controller = new AminulBD\Spider\WordPress\Api\V1\Engines_Controller();
//     $controller->register_routes();
// }

// add_action( 'rest_api_init', 'prefix_register_my_rest_routes' );
