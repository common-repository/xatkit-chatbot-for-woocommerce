<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to manage the settings  of the plugin.
 *
 *
 * @link       https://wordpress.org/plugins/xatkit-chatbot-for-woocommerce/
 * @since      1.0.0
 * @package    Xatkit_Chatbot_For_Woocommerce
 * @subpackage Xatkit_Chatbot_For_Woocommerce\admin
 */

class Xatkit_Chatbot_For_Woocommerce_Admin_Settings {
    protected $plugin_name;

    protected $version;

    protected $admin;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @param Xatkit_Chatbot_For_Woocommerce_Admin $admin Link with the main admin object.
     */
    public function __construct( $plugin_name, $version, $admin ) {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        $this->admin       = $admin;
    }


    /**
     *  Creation of the plugin settings
     */
    public function init_settings() {

        //Registering the settings for each tag
         register_setting(
            'settingsXatkitWooPlugin_group',
            'settingsXatkitWooPlugin',
	                   array(
	                           'sanitize_callback' => array($this,'sanitize_settingsXatkitWooPlugin'),
	                   )
         );
        register_setting(
            'settingsXatkitWooVisibility_group',
            'settingsXatkitWooVisibility',
	                    array(
		                   'sanitize_callback' =>  array($this,'sanitize_settingsXatkitWooVisibility'),
                        )
        );

	    //Defining the fields in each group
        add_settings_section(
            'configurationBot',
            __('Configure your bot','xatkitcom'),
            false, 'xatkit-configuration'
        );

        add_settings_field(
            'urlXatkit',
            __('URL of the Xatkit Server','xatkitcom'),
            array( $this, 'render_urlXatkit_field' ),
            'xatkit-configuration',
            'configurationBot'
        );

        add_settings_field(
            'apikey',
            __('API Key provided by Xatkit platform','xatkitcom'),
            array( $this, 'render_apikey_field' ),
            'xatkit-configuration',
            'configurationBot'
        );

        add_settings_field(
            'enableXatkit',
            __('Activate the bot','xatkitcom'),
            array( $this, 'render_enableXatkit_field' ),
            'xatkit-configuration',
            'configurationBot'
        );

        add_settings_field(
            'dashboard',
            __('Bot configuration panel','xatkitcom'),
            array( $this, 'render_dashboard_field' ),
            'xatkit-configuration',
            'configurationBot'
        );

        add_settings_section(
            'configuration',
            __('Configure the Xatkit widget','xatkitcom'),
            false, 'xatkit-configuration'
        );

        add_settings_field(
            'widgetTitle',
            __('Title of the chat window','xatkitcom'),
            array( $this, 'render_widgetTitle_field' ),
            'xatkit-configuration',
            'configuration'
        );
        add_settings_field(
            'widgetSubTitle',
            __('Subtitle of the chat window','xatkitcom'),
            array( $this, 'render_widgetSubTitle_field' ),
            'xatkit-configuration',
            'configuration'
        );

        add_settings_field(
            'xatkitMedia',
            __('Alternative logo (ideal size 46x46)','xatkitcom'),
            array( $this, 'media_selector_settings_page_callback' ),
            'xatkit-configuration',
            'configuration'
        );

        add_settings_field(
            'colorPicker',
            __('Pick the color of your chatbot box','xatkitcom'),
            array( $this, 'render_color_field' ),
            'xatkit-configuration',
            'configuration'
        );

        add_settings_section(
            'visibility',
            __('Where should Xatkit be displayed in your site?','xatkitcom'),
            false, 'xatkit-visibility'
        );

        add_settings_field(
            'allvisibility',
            __('Select an option','xatkitcom'),
            array( $this, 'render_visibility_all_field'),
            'xatkit-visibility',
            'visibility'
        );

        add_settings_section(
            'visibilityExclude',
            '',
            false, 'xatkit-visibility-exclude'
        );

        add_settings_field(
            'excludeRoutes',
            __('Exclude the bot on specific routes','xatkitcom'),
            array( $this, 'render_visibility_exclude_routes_field' ),
            'xatkit-visibility-exclude',
            'visibilityExclude'
        );

        add_settings_section(
            'howDisplayed',
            __('How should Xatkit be displayed?','xatkitcom'),
            false, 'xatkit-minim'
        );

        add_settings_field(
            'startMinim',
            __('Start the widget always minimized?','xatkitcom'),
            array( $this, 'render_visibility_minimized_field' ),
            'xatkit-minim',
            'howDisplayed'
        );
        add_settings_section(
            'routeMinim',
            '',
            false, 'xatkit-route-minim'
        );
        add_settings_field(
            'routesMinimzed',
            __('Indicate the routes where the widget should start minimized','xatkitcom'),
            array( $this, 'render_visibility_partminim_field' ),
            'xatkit-route-minim',
            'routeMinim'
        );
    }


    /**
     *  Rendering the configuration bot settings  page
     */
    public function configuration_of_the_xatkit_chatbot() {
        // Check required user capability
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
        }

        // Admin Page Layout
        echo '<div class="wrap">' . "\n";
        echo '	<h1>' . get_admin_page_title() . '</h1>' . "\n";

        $active_tab     = sanitize_key(isset( $_GET["tab"] ) ? $_GET["tab"] : "general");
        $active_general = ( $active_tab == 'general' ? 'nav-tab-active' : '' );
        $active_visibility = ( $active_tab == 'visibility' ? 'nav-tab-active' : '' );

        echo ' <h2 class="nav-tab-wrapper"> ' . "\n";
        echo '    <a href="?page=xatkit-configuration&tab=general" class="nav-tab ' . esc_attr($active_general) . '">General</a>' . "\n";
        echo '    <a href="?page=xatkit-configuration&tab=visibility" class="nav-tab ' . esc_attr($active_visibility) . '">Visibility</a>' . "\n";
        echo ' </h2> ' . "\n";


        if ( $active_tab == "general" ) {
            echo '	<form action="options.php" method="post" enctype="multipart/form-data">' . "\n";
            settings_fields( 'settingsXatkitWooPlugin_group' );
            do_settings_sections( 'xatkit-configuration' );
            submit_button();
            echo '</form>' . "\n";
        } else if ( $active_tab == "visibility" ) {
            echo '	<form action="options.php" method="post">' . "\n";
            settings_fields( 'settingsXatkitWooVisibility_group' );
            do_settings_sections( 'xatkit-visibility' );
            echo '<div id="visexclude">';
            do_settings_sections('xatkit-visibility-exclude');
            echo '</div>';
            do_settings_sections( 'xatkit-minim' );
            echo '<div id="minim">';
            do_settings_sections('xatkit-route-minim');
            echo '</div>';
            submit_button();
            echo '</form>' . "\n";
        }
        echo '</div>' . "\n";
    }


    /**
     *  Rendering the options fields
     */
    public function render_enableXatkit_field() {
        // Retrieve the full set of options
        $options = get_option( 'settingsXatkitWooPlugin' );
        // Field output.
        $checked = isset( $options['enableXatkit'] ) ? $options['enableXatkit'] : '0';
        $checked = esc_attr($checked);
        echo '<input id="start" type="checkbox" name="settingsXatkitWooPlugin[enableXatkit]" value="1"' . checked( 1, $checked, false ) . '/>';
    }

    public function render_dashboard_field() {
        echo '<legend id="dashboard">'.__('To configure your bot please visit your bot','xatkitcom') .' '.'<a href=https://ecommerce.xatkit.com/>dashboard</a> </legend>';
    }

    public function render_widgetTitle_field() {
        // Retrieve the full set of options
        $options = get_option( 'settingsXatkitWooPlugin' );
        // Field output.
        // Set default value for this particular option in the group
        $value = isset( $options['widgetTitle'] ) ? $options['widgetTitle'] : 'Chat with us';
        echo '<input type="text" name="settingsXatkitWooPlugin[widgetTitle]" size="50" value="' . esc_html( $value ) . '" />';
    }

    public function render_widgetSubTitle_field() {
        // Retrieve the full set of options
        $options = get_option( 'settingsXatkitWooPlugin' );
        // Field output.
        // Set default value for this particular option in the group
        $value = isset( $options['widgetSubTitle'] ) ? $options['widgetSubTitle'] : __('How can we help you?','xatkitcom');
        echo '<input type="text" name="settingsXatkitWooPlugin[widgetSubTitle]" size="50" value="' . esc_html( $value ) . '" />';
    }

    public function render_color_field() {
        // Retrieve the full set of options
        $options = get_option( 'settingsXatkitWooPlugin' );
        $value = isset( $options['color'] ) ? $options['color'] : '#464646';
        echo '<input id="xatColor" type="text" name="settingsXatkitWooPlugin[color]" size="50" value="' . esc_attr( $value ) . '" />';
    }

    public function render_urlXatkit_field() {
        // Retrieve the full set of options
        $options = get_option( 'settingsXatkitWooPlugin' );
        $value = isset( $options['urlXatkit'] ) ? $options['urlXatkit'] : 'https://ecommerce-bot.xatkit.com';
        echo '<input type="text" name="settingsXatkitWooPlugin[urlXatkit]" size="50" value="' . esc_url( $value ) . '" />';
        echo '<p>' . __('Do NOT change unless you have deployed your own server','xatkitcom') . '</p>';
    }

    public function render_apikey_field() {
        // Retrieve the full set of options
        $options = get_option( 'settingsXatkitWooPlugin' );
        $value = isset( $options['apikey'] ) ? $options['apikey'] : '';
        echo '<input type="text" name="settingsXatkitWooPlugin[apikey]" size="50" value="' . esc_attr( $value ) . '" />';
	    echo '<p>' . __('If you need help finding or getting your API key, review the ','xatkitcom') .'<a href="https://xatkit.com/installation-chatbot/" target="_blank">installation instructions</a>'.'</p>';
    }

    public function render_visibility_minimized_field() {
         // Retrieve the full set of options
        $options = get_option( 'settingsXatkitWooVisibility' );
        echo '<div class="visibilityItem minim">';
        $checked = isset( $options['visibility']['minim'] ) ? $options['visibility']['minim'] : '0';
        $checked = esc_attr($checked);
        echo '<input type="checkbox" id="alwaysmin" name="settingsXatkitWooVisibility[visibility][minim]" value="1"' . checked( 1, $checked, false ) . '/>';
        echo '</div>';
    }

    public function render_visibility_all_field() {
         // Retrieve the full set of options
        $options = get_option( 'settingsXatkitWooVisibility' );
        // Front Page
        echo '<div class="visibilityItem allPages">';
        if (isset($options['visibility']['allPages'])) {
            if (esc_attr($options['visibility']['allPages']) == 'front') {
                echo '<input type="radio" id="all" name="settingsXatkitWooVisibility[visibility][allPages]" value="all">
                <label for="all">' . __('Display in all pages','xatkitcom') . '</label><br>
                <input type="radio" id="filters" name="settingsXatkitWooVisibility[visibility][allPages]" value="front" checked>
                <label for="filters"> ' . __('Display only in the front page','xatkitcom') . ' </label><br>';
            } else {
                echo '<input type="radio" id="all" name="settingsXatkitWooVisibility[visibility][allPages]" value="all" checked>
                <label for="all">Display in all pages</label><br>
                <input type="radio" id="filters" name="settingsXatkitWooVisibility[visibility][allPages]" value="front">
                <label for="filters">' . __('Display only in the front page','xatkitcom') . ' </label><br>';
            }
        } else {
             echo '<input type="radio" id="all" name="settingsXatkitWooVisibility[visibility][allPages]" value="all" checked>
                <label for="all">' . __('Display in all pages','xatkitcom') . '</label><br>
                <input type="radio" id="filters" name="settingsXatkitWooVisibility[visibility][allPages]" value="front">
                <label for="filters">' . __('Display only in the front page','xatkitcom') . '</label><br>';
        }
        echo '</div>';
    }

    public function render_visibility_exclude_routes_field() {
         // Retrieve the full set of options
        $options = get_option( 'settingsXatkitWooVisibility' );
        echo '<div class="visibilityItem route">';
        echo '<select name="settingsXatkitWooVisibility[visibility][excluderoutes][]" id="excludeRoutes" multiple>';
            if (isset($options['visibility']['excluderoutes'])){
                    foreach($options['visibility']['excluderoutes'] as $route) {
                            echo '<option value="'.esc_url($route).'" selected>'.esc_url($route).'  </option>';
                    }
            }
        echo '</select></div>';
        echo '<p>' . __('For example, use _front to exclude from the front page or use "/fr/" to exclude an entire language.  Use at least 4 characters to create a tag','xatkitcom') . '</p>';
    }


    public function  render_visibility_partminim_field() {
         // Retrieve the full set of options
        $options = get_option( 'settingsXatkitWooVisibility' );
        // Selection by Taxonomies

        if (!isset($options['visibility']['partmin'])) {
	        $options['visibility']['partmin'] = [];
        }
        echo '<div id="partialMinim" class="visibilityItem minim">';
        echo '<select name="settingsXatkitWooVisibility[visibility][partmin][]" id="partialMin" multiple>';
	    if (isset($options['visibility']['partmin'])) {
		    foreach ( $options['visibility']['partmin'] as $var ) {
			    echo '<option value="' . esc_attr($var) . '" selected>' . esc_attr($var) . '</option>';
		    }
	    }
        echo '</select></div>';
        echo '<p>' . __('For example, use _front to minimize in the front page or use /fr/ to minimize an entire language. Use at least 4 characters to create a tag','xatkitcom') . '</p>';
    }

    function media_selector_settings_page_callback() {

        // Save attachment ID
        if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) ) :
            update_option( 'settingsXatkitWooPlugin[media]', absint( $_POST['image_attachment_id'] ) );
        endif;
        $options = get_option( 'settingsXatkitWooPlugin' );
        ?>
            <div class='image-preview-wrapper'>
                <img id='image-preview' src='<?php echo wp_get_attachment_url( esc_attr($options['media']) ); ?>' height='100'>
            </div>
            <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
            <input type='hidden' name='settingsXatkitWooPlugin[media]' id='image_attachment_id' value='<?php echo esc_attr($options['media']); ?>'>

        <?php

    }

    function sanitize_settingsXatkitWooVisibility($input)
    {
	    // Initialize the new array that will hold the sanitize values
	    $new_input = array();

	    // Loop through the input and sanitize each of the values
	    foreach ( $input as $key => $subInput ) {
	        foreach($subInput as $subkey => $val) {
		        if(strcmp($subkey,'excluderoutes')==0)
                {
                    foreach($input[$key][$subkey] as $rIndex => $route)
                    {
	                    $new_input[$key][$subkey][$rIndex]= esc_url_raw( $route );
                    }
                }
                else if(strcmp($subkey,'partmin')==0) {
	                     foreach($input[$key][$subkey] as $rIndex => $route)
	                     {
		                     $new_input[$key][$subkey][$rIndex]= sanitize_text_field( $route );
	                     }
                    }
                    else {
	                    $new_input[ $key ][ $subkey ] = sanitize_text_field( $val );
                    }
	        }
	    }

	    return $new_input;
    }

	function sanitize_settingsXatkitWooPlugin($input)
	{
		// Initialize the new array that will hold the sanitize values
		$new_input = array();

		// Loop through the input and sanitize each of the values
		foreach ( $input as $key => $val ) {
			if(strcmp($key,'urlXatkit')==0) {
			  $new_input[ $key ] = esc_url_raw( $val );
			}
			else {
			    $new_input[ $key ] = sanitize_text_field( $val );
			}
		}

		return $new_input;
	}

}

