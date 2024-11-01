<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Xatkit_Chatbot_For_Woocommerce
 * @subpackage Xatkit_Chatbot_For_Woocommerce\includes
 * @package
 */

class Xatkit_Chatbot_For_Woocommerce_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'Xatkit_Chatbot_For_Woocommerce',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }
}
