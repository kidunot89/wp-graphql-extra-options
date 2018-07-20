<?php

namespace WPGraphQLExtra\Type\ThemeMod;

require_once 'theme-mod-type.php';

use WPGraphQL\Types;

/**
 * Class ThemeModQuery
 *
 * @package WPGraphQL\Type\ThemeMod
 */
class ThemeModQuery {

	/**
	 * Method that returns the root query field definition
	 * for ThemeMod
	 *
	 * @access public
	 * @param array $allowed_mods
	 *
	 * @return array $root_query
	 */
	public static function root_query( $allowed_mods ) {

		return [
			'type'        => Types::themeMod( '\\WPGraphQLExtra\\Type\\ThemeMod\\ThemeModType', $allowed_mods ),
			'resolve'     => function () {
				return true;
			},
		];
	}
}
