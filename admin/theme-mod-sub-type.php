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
 * This sets up the theme modification sub-types
 * Nav Menu Locations
 * Custom Logo
 *
 * @since 0.3.1
 * @package WPGraphQLExtra\Type
 */
class ThemeModSubType extends WPObjectType {

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
	 * ThemeModType constructor.
	 *
	 * @access public
	 */
	public function __construct( $type_name ) {
    /**
		 * Set the type_name
		 *
		 * @since 0.0.1
		 */
		self::$type_name = $type_name;

		$config = [
			'name'        => self::$type_name,
			'fields'      => self::fields( $type_name ),
			'description' => __( 'All of registered theme modifications', 'wp-graphql-extra-options' ),
		];

    parent::__construct( $config );
    
  }

   /**
	 * Retrieve the fields for the ThemeModSubType
	 *
	 * @param $mods
	 *
	 * @access private
	 * @return \GraphQL\Type\Definition\FieldDefinition|mixed|null
	 */
  private static function fields( string $type_name ) {

    /**
     * Dynamically field definitions for specific types
     */
    switch( $type_name ) {
      
      case 'NavMenuLocations':
        return self::nav_menu_location();
      default:
        return null;

    }

  }

  /**
   * This defines the fields for the NavMenuLocation sub type
   *
   * @return array  \GraphQL\Type\Definition\FieldDefinition|mixed|null
   */
  private static function nav_menu_location() {
    
    return [
      'name' => [
        'type'        => Types::string(),
        'description' => __( 'name of menu location', 'wp-graphql-extra-options' ),
        'resolve'     => function ( $root, $args, AppContext $context, ResolveInfo $info ) {
          return ( ! empty( $root[ 'location_name' ] ) ) ? $root[ 'location_name' ] : null;
        }
      ],
      'menu' => [
        'type'        => Types::menu(),
        'description' => __( 'menu set to menu location', 'wp-graphql-extra-options' ),
        'resolve'     => function ( $root, $args, AppContext $context, ResolveInfo $info ) {
          if ( ! empty( $root[ 'menu_id' ] ) ) {
            return DataSource::resolve_term_object( $root[ 'menu_id' ], 'nav_menu' );
          }
          return null;
        }
      ]
    ];

  }

}