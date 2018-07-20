<?php
	use WPGraphQLExtra\Type\ThemeMod\ThemeModQuery;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://axistaylor.com
 * @since      1.0.0
 *
 * @package    Wp_Graphql_Extra_Options
 * @subpackage Wp_Graphql_Extra_Options/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Graphql_Extra_Options
 * @subpackage Wp_Graphql_Extra_Options/admin
 * @author     Geoff Taylor <geoffrey.taylor@outlook.com>
 */
class Wp_Graphql_Extra_Options_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'wp_graphql_extra_options';

	/**
	 * The delimiter
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$delimiter
	 */
	private $delimiter = '<->';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Graphql_Extra_Options_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Graphql_Extra_Options_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-graphql-extra-options-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Graphql_Extra_Options_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Graphql_Extra_Options_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-graphql-extra-options-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
	
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'WPGraphQL Extra Options Settings', 'wp-graphql-extra-options' ),
			__( 'WPGraphQL Extra Options', 'wp-graphql-extra-options' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {

		include_once 'partials/wp-graphql-extra-options-admin-display.php';
	
	}

	/**
	 * Register option page sections
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {

		// Add a General section
		add_settings_section(
			$this->option_name . '_general',
			__( 'General', 'wp-graphql-extra-options' ),
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->option_name . '_selected',
			__( 'Selected Settings', 'wp-graphql-extra-options' ),
			array( $this, $this->option_name . '_selected_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_selected' )
		);

		add_settings_field(
			$this->option_name . '_theme_mods',
			__( 'Theme Mods', 'wp-graphql-extra-options' ),
			array( $this, $this->option_name . '_theme_mods_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_theme_mods' )
		);

		add_settings_field(
			$this->option_name . '_exclude_mods',
			__( 'Exclude Theme Mods', 'wp-graphql-extra-options' ),
			array( $this, $this->option_name . '_exclude_mods_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_exclude_mods' )
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_selected',
			array( $this, $this->option_name . '_sanitize_selected' )
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_theme_mods',
			array( $this, $this->option_name . '_sanitize_theme_mods' )
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_exclude_mods',
			array( $this, $this->option_name . '_sanitize_exclude_mods' )
		);

	}

	public function __get( $name ) {
		
		switch( $name ) {

			case '_selected':
				if (! isset( $this->$name )) {
					$this->$name = json_decode( get_option( $this->option_name . '_selected', '' ), true );
				}
				return $this->$name;
			
			case '_theme_mods':
				if (! isset( $this->$name )) {
					$this->$name = get_option( $this->option_name . $name, false );
				}
				return $this->$name;

			case '_exclude_mods':
				if (! isset( $this->$name )) {
					$this->$name = json_decode( get_option( $this->option_name . '_exclude_mods', '' ), true );
				}
				return $this->$name;

		}

	}

	/**
	 * Add selected settings to allSettings type schema
	 *
	 * @return array 	filtered args
	 */
	public function graphql_settings_fields( $fields ) {

		$settings = $this->_selected;

		$field_keys = array_keys( $fields );
		foreach( $settings as $key => $value ) {
			if ( ! in_array( $key, $field_keys )) {
				/**
				 * Add field
				 */
				$name = str_replace( '_', '', lcfirst( ucwords( esc_textarea( $key ), '_' ) ) );
				$type = esc_textarea( $value[ 'type' ] );
				$description = esc_textarea( $value[ 'description' ] );
				$fields[ $name ] = [
					'type' => \WPGraphQL\Types::get_type( $type ),
					'description' => $description,

					/**
					 *  Dynamic resolver function copied from WPGraphQL plugin's Settings type
					 */
					'resolve' => function() use ( $value, $key) {
						
						$option = ! empty( $key ) ? get_option( $key ) : null;

						switch ( $value['type'] ) {
							case 'integer':
								$option = absint( $option );
								break;
							case 'string':
								$option = (string) $option;
								break;
							case 'boolean':
								$option = (boolean) $option;
								break;
							case 'number':
								$option = (float) $option;
								break;
						}

						return $option;
					},
				];
			}
		}

		return $fields;

	}

	/**
	 * Add themeMods to root type schema
	 *
	 * @return array 	filtered args
	 */
	public function graphql_root_queries($fields) {
		if ( ! $this->_theme_mods ) {
			return $fields;
		}
		
		/**
		 * get allowed theme_mods
		 */
		$exclude_mods = $this->_exclude_mods;
		$all_mods = array_keys ( get_theme_mods() );

		$allowed_mods = [];
		if ( ! empty ( $exclude_mods ) ) {
			foreach ($all_mods as $mod ) {
				if ( ! in_array( $mod, $exclude_mods ) ) {
					$allowed_mods[] = $mod;
				}
			}
		} else {
			$allowed_mods = $all_mods;
		}

		/**
		 * Build ThemeModQuery field with allowed theme modifications
		 */
		if ( ! empty ( $allowed_mods ) ) {
			$fields[ 'themeMods' ] = ThemeModQuery::root_query( $allowed_mods );
		}
		return $fields;
	}

	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function wp_graphql_extra_options_general_cb() {

	}

	/**
	 * Render the textarea field for selected option
	 *
	 * @since  1.0.0
	 */
	public function wp_graphql_extra_options_selected_cb() {
		$settings = $this->_selected;
		?>		
			<textarea class="wpgeo-textarea" name="<?php echo $this->option_name . '_selected' ?>" id="<?php echo $this->option_name . '_selected' ?>"><?php
				if( is_array( $settings ) ) {
					foreach( $settings as $key => $value ) {
						$name = esc_textarea( $key );
						$type = esc_textarea( $value[ 'type' ] );
						$description = esc_textarea( $value[ 'description' ] );
						echo "{$name}{$this->delimiter}{$type}{$this->delimiter}{$description}\r\n";
					}
				}
			?></textarea>
			<br>
			<span class="description">
				<?php echo "Enter slug of desired settings in \"name{$this->delimiter}type{$this->delimiter}description\" format, separated by a new line." ?>
				<br />
				<?php echo "Ex. \"page_on_front{$this->delimiter}integer{$this->delimiter}static page used as home page" ?>
				<br />
				<?php echo "page_for_posts{$this->delimiter}integer{$this->delimiter}page used to display blog posts\"." ?>
				<br />
				<?php echo '<a href="https://codex.wordpress.org/Option_Reference" target="_blank">Option Reference</a>' ?>
			</span>
		<?php
	}

	/**
	 * Render checkbox for _theme_mods field
	 *
	 * @since  1.0.1
	 */
	public function wp_graphql_extra_options_theme_mods_cb() {
		$checked = ( $this->_theme_mods ) ? 'checked': '';
		?>		
			<input type="checkbox" <?php echo $checked ?> name="<?php echo $this->option_name . '_theme_mods' ?>" id="<?php echo $this->option_name . '_theme_mods' ?>" />
			<br>
			<span class="description">
				<?php _e( 'Check to add theme modification to WPGraphQL Types schema', 'wp-graphql-extra-options' ) ?>
			</span>
		<?php
	}

	/**
	 * Render exclude_mod textarea
	 *
	 * @since  1.0.1
	 */
	public function wp_graphql_extra_options_exclude_mods_cb() {
		$exclude_mods = $this->_exclude_mods;
		?>		
			<textarea class="wpgeo-textarea" name="<?php echo $this->option_name . '_exclude_mods' ?>" id="<?php echo $this->option_name . '_exclude_mods' ?>"><?php
				if( is_array( $exclude_mods ) ) {
					foreach( $exclude_mods as $mod ) {
						echo esc_textarea( $mod ) . PHP_EOL;
					}
				}
			?></textarea>
			<br>
			<span class="description">
				<?php echo _e( 'Enter theme_mod name to be excluded, separate by new line.' ) ?>
			</span>
		<?php
	}

	/**
	 * Sanitize the selected value before being saved to database
	 *
	 * @param  string	$selected $_POST value
	 * @since  				1.0.0
	 * @return string Sanitized value
	 */
	public function wp_graphql_extra_options_sanitize_selected( $selected ) {

		$lines = explode( PHP_EOL, $selected);
		$settings = array();
		foreach( $lines as $line ) {
			
			/**
			 * Validate and sanitize line
			 */
			$parts = explode( $this->delimiter, $line );
			
			$length = count ( $parts );
			if ( $length < 2 || $length > 3 ) {
				// TODO - throw invalid number of arguments error
				continue;
			}

			if ( false === $parts[0] || in_array( $parts[0], $settings ) ) {
				// TODO - throw invalid setting name error
				continue;
			}
			$name = sanitize_text_field( $parts[0] );
			$settings[ $name ] = [];
	
			if ( false === $parts[1] ) {
				// TODO - throw invalid setting type error
				continue;
			}
			$settings[ $name ][ 'type' ] = sanitize_text_field( $parts[1] );

			if ( false !== $parts[2] ) {
				$settings[ $name ][ 'description' ] = sanitize_text_field( $parts[2] );
			}
		}

		/**
		 * Get all registered settings
		 */
		$registered_keys = array_keys( wp_load_alloptions() );
		
		/**
		 * Validate user input
		 */
		$valid = array();
		foreach( $settings as $name => $value) {
			// Skip non-existing or duplicates
			if ( ! in_array( $name, $registered_keys, true ) || in_array( $name, $valid ) ) {
				// TODO: and error output for invalid entries
				continue;
			}

			$valid[$name] = $value;
		}

		if ( ! empty( $valid )) {

			$selected = json_encode( $valid );
		
		}	else {
			
			$selected = '';

		}
		
		return $selected;

	}

	/**
	 * Sanitize the _theme_mods value before being saved to database
	 *
	 * @param  string	$theme_mods $_POST value
	 * @since  				1.0.1
	 * @return string Sanitized value
	 */
	public function wp_graphql_extra_options_sanitize_theme_mods( $theme_mods ) {

		if ( $theme_mods ) return true;
		return false;

	}

	/**
	 * Sanitize the exclude_mods value before being saved to database
	 *
	 * @param  string	$exclude_mods $_POST value
	 * @since  				1.0.0
	 * @return string Sanitized value
	 */
	public function wp_graphql_extra_options_sanitize_exclude_mods( $exclude_mods ) {

		$mods = explode( PHP_EOL, $exclude_mods);

		/**
		 * Get all registered settings
		 */
		$all_mods = array_keys( get_theme_mods() );
		
		/**
		 * Validate user input
		 */
		$valid = array();
		foreach( $mods as $mod) {
			// Skip non-existing
			if ( ! in_array( $mod, $all_mods, true ) || in_array( sanitize_text_field( $mod ), $valid ) ) {
				// TODO: and error output for invalid entries
				continue;
			}

			$valid[] = sanitize_text_field( $mod );
		}

		if ( ! empty( $valid )) {

			$exclude_mods = json_encode( $valid );
		
		}	else {
			
			$exclude_mods = '';

		}
		
		return $exclude_mods;

	}

}
