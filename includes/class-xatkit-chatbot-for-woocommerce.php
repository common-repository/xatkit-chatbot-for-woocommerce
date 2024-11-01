<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wordpress.org/plugins/xatkit-chatbot-for-woocommerce/
 * @since      1.0.0
 * @package    Xatkit_Chatbot_For_Woocommerce
 * @subpackage Xatkit_Chatbot_For_Woocommerce\includes
 */

class Xatkit_Chatbot_For_Woocommerce {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @var      Xatkit_Chatbot_For_Woocommerce_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     */
    public function __construct() {
        if ( defined( 'XATKIT_CHATBOT_FOR_WOOCOMMERCE_VERSION' ) ) {
            $this->version = XATKIT_CHATBOT_FOR_WOOCOMMERCE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'xatkit-chatbot-for-woocommerce';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Xatkit_Chatbot_Connector_Loader. Orchestrates the hooks of the plugin.
     * - Xatkit_Chatbot_Connector_i18n. Defines internationalization functionality.
     * - Xatkit_Chatbot_Connector_Admin. Defines all hooks for the admin area.
     * - Xatkit_Chatbot_Connector_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xatkit-chatbot-for-woocommerce-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xatkit-chatbot-for-woocommerce-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-xatkit-chatbot-for-woocommerce-admin.php';


        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-xatkit-chatbot-for-woocommerce-public.php';
        $this->loader = new Xatkit_Chatbot_For_Woocommerce_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Xatkit_Chatbot_Connector_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new Xatkit_Chatbot_For_Woocommerce_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @access   private
     */
    protected function define_admin_hooks() {
        $plugin_admin = new Xatkit_Chatbot_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $plugin_settings = null;
        $plugin_settings = new Xatkit_Chatbot_For_Woocommerce_Admin_Settings( $this->get_plugin_name(), $this->get_version(), $plugin_admin );
        $this->loader->add_action( 'admin_init', $plugin_settings, 'init_settings' );    // Registering also the plugin settings
        $plugin_display = new Xatkit_Chatbot_For_Woocommerce_Admin_Display( $this->get_plugin_name(), $this->get_version(), $plugin_admin, $plugin_settings );
        $this->loader->add_action( 'admin_menu', $plugin_display, 'init_admin_menu' );    // Registering also the main plugin menu
        $this->define_additional_admin_hooks( $plugin_admin );
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @access   private
     */
    protected function define_public_hooks() {
        $plugin_public = new Xatkit_Chatbot_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->define_additional_public_hooks( $plugin_public );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Xatkit_Chatbot_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    protected function define_additional_public_hooks( $plugin_public ) {

        //We have to make sure that the script tags are below the xatkit div so we put the div as top priority
        $this->loader->add_action( 'wp_footer', $plugin_public, 'chat_div_window', 1 );
        $this->loader->add_action( 'wp_footer', $plugin_public, 'chat_window', 2 );

        //	$this->loader->add_action( 'wp_body_open', $plugin_public, 'chat_div_window' ); <- This is a better way to do it
        //but it doesn't work if a theme is not calling wp_body_open as part of the page template
    }

    protected function define_additional_admin_hooks( $plugin_admin ) {
     //   $this->loader->add_action( 'rest_api_init', $plugin_admin, 'definition_custom_endpoints', 1 );
     //   $this->loader->add_action( 'admin_post_xatkit-custom-form', $plugin_admin, 'manage_custom_questions' ); //hooks to intercept the form submission
    }


}

