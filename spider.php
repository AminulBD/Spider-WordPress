<?php
/**
 * Spider
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
const SPIDER_VERSION = '1.0.0';
define( 'SPIDER_URL', plugin_dir_url( __FILE__ ) );
define( 'SPIDER_PATH', plugin_dir_path( __FILE__ ) );
define( 'SPIDER_ASSET_URL', plugin_dir_url( __FILE__ ) . 'public/' );

function spider() {
	return \AminulBD\Spider\WordPress\WordPress::init([
		'version' => SPIDER_VERSION,
	]);
}

// Kick
spider();

include __DIR__ . '/.dev.php';
