<?php

namespace WPGraphQLExtra\Type\ThemeMod;

use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\Data\DataSource;
use WPGraphQL\Type\WPObjectType;
use WPGraphQL\Types;

/**
 * Class ThemeModsType
 *
 * This sets up the theme modification type
 *
 * @package WPGraphQLExtra\Type
 */
class ThemeModType extends WPObjectType {

	/**
	 * Holds the type name
	 *
	 * @var string $type_name
	 */
	private static $type_name;

	/**
	 * Holds the $fields definition for the SettingsType
	 *
	 * @var array $fields
	 * @access private
	 */
	private static $fields;

	/**
	 * SettingsType constructor.
	 *
	 * @access public
	 */
	public function __construct() {
    /**
		 * Set the type_name
		 *
		 * @since 1.0.1
		 */
		self::$type_name = 'ThemeMods';

		$config = [
			'name'        => self::$type_name,
			'fields'      => self::fields(),
			'description' => __( 'All of registered theme modifications', 'wp-graphql-extra-options' ),
		];

    parent::__construct( $config );
    
  }

  /**
	 * This defines the fields for the ThemeMods type
	 *
	 * @param $mods
	 *
	 * @access private
	 * @return \GraphQL\Type\Definition\FieldDefinition|mixed|null
	 */
	private static function fields() {

		/**
		 * Define $fields
		 */
    $fields = [];
		
		/**
		 * Get theme mods keys
		 */
		$theme_mods = array_keys ( get_theme_mods() );
		

    if ( ! empty( $theme_mods ) && is_array( $theme_mods ) ) {

			/**
			 * Loop through the $theme_mods and build the setting with
			 * proper fields
			 */
      foreach( $theme_mods as $mod ) {

        $field_key = lcfirst( str_replace( '_', '', ucwords( $mod, '_' ) ) );

				if ( ! empty( $mod ) && ! empty( $field_key ) ) {

					/**
					 * Dynamically build the individual setting and it's fields
					 * then add it to $fields
					 */
					$fields[ $field_key ] = [
						'type'        => Types::get_type( 'string' ),
            'resolve'     => function( $root, $args, AppContext $context, ResolveInfo $info ) use( $mod ) {
							
							/**
               * Retrieve theme modification.
               */
							$theme_mod = get_theme_mod( $mod, 'none' );
							if ( is_array( $theme_mod ) ) {
							
								$theme_mod = json_encode( $theme_mod );
							
							}
							return $theme_mod;

            },
          ];

        }

      }

      /**
       * Pass the fields through a filter to allow for hooking in and adjusting the shape
       * of the type's schema
       */
      self::$fields = self::prepare_fields( $fields, self::$type_name );
    }

    return ! empty( self::$fields ) ? self::$fields : null;

  }

}

