<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to manage the admin-facing aspects of the plugin.
 *
 *
 * @link       https://wordpress.org/plugins/xatkit-chatbot-for-woocommerce/
 * @since      1.0.0
 * @package    Xatkit_Chatbot_For_Woocommerce
 * @subpackage Xatkit_Chatbot_For_Woocommerce\admin
 */

class Xatkit_Chatbot_For_Woocommerce_Admin_Display {
    protected $plugin_name;

    protected $version;

    protected $admin;

    protected $settings;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @param Xatkit_Chatbot_For_Woocommerce_Admin $admin Link with the main admin object.
     */
    public function __construct( $plugin_name, $version, $admin, $settings ) {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        $this->admin       = $admin;
        $this->settings    = $settings;
    }


    /**
     *  Creation of the admin menu items
     */
    public function init_admin_menu() {

        //adding the top menu (pointing to the configuration page)
        add_menu_page(
            'Xatkit Configuration',
            'Xatkit',
            'manage_options',
            'xatkit-configuration',
            array(
                $this->settings,
                'configuration_of_the_xatkit_chatbot'
            ),//top menu option links also to the configuration page directly
            'dashicons-admin-users',
            null
        );

    }

}
