<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/xatkit-chatbot-for-woocommerce/
 * @since      1.0.0
 * @package    Xatkit_Chatbot_For_Woocommerce
 * @subpackage Xatkit_Chatbot_For_Woocommerce\admin
 */

class Xatkit_Chatbot_For_Woocommerce_Admin {

    /**
     * The ID of this plugin.
     *
     * @access   protected
     * @var      string $plugin_name The ID of this plugin.
     */
    protected $plugin_name;

    /**
     * The version of this plugin.
     *
     * @access   protected
     * @var      string $version The current version of this plugin.
     */
    protected $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        $this->load_dependencies();

    }

    protected function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class-xatkit-chatbot-connector-for-woocommerce-admin-display.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class-xatkit-chatbot-for-woocommerce-admin-settings.php';
    }


    /**
     * Register the stylesheets for the admin area
     *
     */
    public function enqueue_styles( $hook ) {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'adminCss', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css');
	    wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'assets/css/select2.min.css', array(), null, 'all' );

	  //  wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
    }


    /**
     * Register the JavaScript for the admin area.
     *
     */
    public function enqueue_scripts( $hook ) {
//        wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery') );
	    wp_enqueue_script('select2', plugin_dir_url( __FILE__ ) . 'assets/js/select2.min.js', array( 'jquery' ), null, false );

	    wp_enqueue_script('adminBot', plugin_dir_url( __FILE__ ) . 'assets/js/adminBot.js', array( 'wp-color-picker' ), null, false );
        wp_enqueue_media();
        $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
        wp_add_inline_script( 'adminBot', 'const set_to_post_id = '.$my_saved_attachment_post_id , 'before' );

    }




}

