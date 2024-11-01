<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wordpress.org/plugins/xatkit-chatbot-for-woocommerce/
 * @since             1.0.0
 * @package           Xatkit_Chatbot_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Xatkit Chatbot for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/xatkit-chatbot-for-woocommerce
 * Description:       Plugin to integrate a Xatkit eCommerce bot in WooCommerce
 * Version:           1.0.4
 * Author:            Xatkit
 * Author URI:        https://xatkit.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       xatkitcom
 * Domain Path:       /languages
 *
 * This plugin is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or  any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
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
define( 'XATKIT_CHATBOT_FOR_WOOCOMMERCE', '1.0.4' );

add_action('plugins_loaded', 'xatkit_chatbot_for_woocommerce_translate');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-xatkit-chatbot-for-woocommerce.php
 */
function activate_xatkit_chatbot_for_woocommerce() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-xatkit-chatbot-for-woocommerce-activator.php';
    Xatkit_Chatbot_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-xatkit-chatbot-for-woocommerce-deactivator.php
 */
function deactivate_xatkit_chatbot_for_woocommerce() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-xatkit-chatbot-for-woocommerce-deactivator.php';
    Xatkit_Chatbot_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_xatkit_chatbot_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_xatkit_chatbot_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-xatkit-chatbot-for-woocommerce.php';




/**
 * Internacionalitzation.
 */
function xatkit_chatbot_for_woocommerce_translate() {
  $text_domain = 'xatkitcom';
  $path_languages = basename(dirname(__FILE__)) . '/languages/';
  load_plugin_textdomain($text_domain, FALSE, $path_languages);
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_xatkit_chatbot_for_woocommerce() {

    $plugin = new Xatkit_Chatbot_For_Woocommerce();
    $plugin->run();

}

run_xatkit_chatbot_for_woocommerce();








