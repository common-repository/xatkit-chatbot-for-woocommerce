<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/xatkit-chatbot-for-woocommerce/
 * @since      1.0.0
 *
 * @package    Xatkit_Chatbot_For_Woocommerce
 * @subpackage Xatkit_Chatbot_For_Woocommerce\public
 */

class Xatkit_Chatbot_For_Woocommerce_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The ID of this plugin.
     */
    protected $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of this plugin.
     */
    protected $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
	    wp_enqueue_style( 'xatkit_css', plugin_dir_url( __FILE__ ) . 'css/xatkit.min.css', array(), null, 'all' );
	// Not using the CDN anymore
    //    wp_enqueue_style( 'xatkit_css', 'https://dev.xatkit.com/static/xatkit.min.css', array(), null, 'all' );
        wp_enqueue_style( $this->plugin_name . '_public_aux', plugin_dir_url( __FILE__ ) . 'css/xatkit-chatbot-for-woocommerce-public.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        //wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/xatkit-chatbot-connector-public.js', array( 'jquery' ), $this->version, false );
	    wp_enqueue_script( 'xatkit_js', plugin_dir_url( __FILE__ ) . 'js/xatkit.min.js', array( 'jquery' ), null, false );
	   // wp_enqueue_script( 'xatkit_js', 'https://dev.xatkit.com/static/xatkit.min.js', false , null, false );
    }

    public function chat_div_window() {
        $options   = get_option( 'settingsXatkitWooPlugin' );
        $enabled   = isset( $options['enableXatkit'] );
        $serverURL = trim( $options['urlXatkit'] );
        if ( $enabled && $serverURL !== '' ) {
            ?>
            <div id="xatkit-chat"></div>
            <?php
        }
    }


    public function chat_window() {

        $options_visibility = get_option( 'settingsXatkitWooVisibility' );

        // Get Configured Options
        $options_general = get_option( 'settingsXatkitWooPlugin' );
        $configurations = [
            'serverUrl' => esc_url(trim( $options_general['urlXatkit']) ),
            'enabled'   => esc_attr(isset( $options_general['enableXatkit'] )),
            'title'     => esc_html($options_general['widgetTitle']),
            'subtitle'  => esc_html($options_general['widgetSubTitle']),
            'logo'      => esc_html( wp_get_attachment_url( esc_attr($options_general['media']) )),
            'color'     => esc_attr($options_general['color']),
            'apikey'    => esc_attr($options_general['apikey']),
            'minimized' => esc_attr(isset($options_visibility['visibility']['minim']) ? $options_visibility['visibility']['minim'] : 0),
        ];

        // visibility
        $useFilters = isset($options_visibility['visibility']['allPages']) ? $options_visibility['visibility']['allPages'] : 'all';
        $excludeRoutes = isset($options_visibility['visibility']['excluderoutes']) ? $options_visibility['visibility']['excluderoutes'] : [];
        // Check Minimized
        $partialMinRoutes = isset($options_visibility['visibility']['partmin']) ? $options_visibility['visibility']['partmin'] : [];
        if ($configurations['minimized'] != 1) {
            foreach ($partialMinRoutes as $route) {
                if( !(strpos($_SERVER['REQUEST_URI'] , $route )===false))
                {
                    $configurations['minimized'] = 1;
                    break;
                }
                if (!(strpos($route, '_front')===false)) {
                    if(is_front_page()) {
	                    $configurations['minimized'] = 1;
	                    break;
                    }
                } 
            }
        }

        $user = wp_get_current_user();
        if ( $user->exists() ) // $username=$user->nickname;
        {
            $configurations['username'] = 'registered';
        } // For now we don't track the data of specific user profiles
        else {
            $configurations['username'] = 'anonymous';
        }

        // Check visibility options
        if ( $configurations['enabled'] && $configurations['serverUrl'] !== '' ) {  // Check if bot is configured
            if ($useFilters == 'all') { // if users wants to display on every page we check if there are excluded routes
              if (empty($excludeRoutes)) {
                  $this->printWidget($configurations);
              }
              else {
                $excluded = FALSE;
                foreach ($excludeRoutes as $route) {
                    if((strpos ( $_SERVER['REQUEST_URI'] , $route ))) $excluded = TRUE;
                    if ($route == '_front') {
                        if(is_front_page())  $excluded = TRUE;
                    } 
                }
                if (!$excluded) {
                    $this->printWidget($configurations);
                }
              }
            } else { //means that we´re in the useFilters ='front' scenario
                if (is_front_page() ) { // Keep in mind the diff between is_front_page and is_home as it may confuse some users
                    $this->printWidget($configurations);
                }
            }

        }

    }

    public function printWidget($configurations) {
        ?>
        <script type='text/javascript'>
            // Renders the chat widget, see https://github.com/xatkit-bot-platform/xatkit-chat-widget for the parameters information
            xatkit.renderXatkitWidget({
                server: "<?php echo esc_url($configurations['serverUrl']) . '/chat-handler';?>",
                apiKey: "<?php echo esc_html($configurations['apikey']); ?>",
                username: "<?php echo esc_html($configurations['username']); ?>",
                widget: {
                    title: "<?php echo addslashes( esc_html($configurations['title']) ); ?>",
                    subtitle: "<?php echo addslashes( esc_html($configurations['subtitle'])); ?>",
                    startMinimized: <?php echo esc_attr($configurations['minimized']); ?>,
                    images: {
                        <?php echo( esc_attr($configurations['logo']) == "" ? '' : 'profileAvatar:' . "'" . esc_attr($configurations['logo']) . "', launcherImage:" . "'" . esc_attr($configurations['logo']) . "'" ); ?>
                    }
                },
                location: {
                    hostname: "<?php echo site_url(); ?>",
                    origin: "<?php echo site_url(); ?>"
                },
                storage: {
                    autoClear: false
                }
            })
        </script>
        <?php
        if (!empty($configurations['color'])) {
            ?>
            <style>
            .xatkit-conversation-container .xatkit-header {
            background-color: <?php echo esc_attr($configurations['color']); ?>;
            }
            .xatkit-full-screen .xatkit-close-button {
            background-color: <?php echo esc_attr($configurations['color']); ?>;
            }
            .xatkit-conversation-container .xatkit-close-button {
            background-color: <?php echo esc_attr($configurations['color']); ?>;
            }
            .xatkit-quick-list-button > .xatkit-quick-button {
            border: 2px solid <?php echo esc_attr($configurations['color']); ?>;
            }
            .xatkit-quick-list-button > .xatkit-quick-button:active {
            background: <?php echo esc_attr($configurations['color']); ?>;
            }
            .xatkit-quick-list-button > .xatkit-quick-button-selected {
            background: <?php echo esc_attr($configurations['color']); ?>;
            }
            .xatkit-quick-list-button > .xatkit-quick-button-selected:hover {
            background: <?php echo esc_attr($configurations['color']); ?>;
            }
            .xatkit-widget-container > .xatkit-launcher {
            background-color: <?php echo esc_attr($configurations['color']); ?>;
            }
            .xatkit-widget-container > .xatkit-launcher:hover {
            background-color: <?php echo esc_attr($configurations['color']); ?>;
            }
            .xatkit-widget-container > .xatkit-launcher:focus {
            background-color: <?php echo esc_attr($configurations['color']); ?>;
            }
        </style>

            <?php
        }
    }
}

