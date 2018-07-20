# WPGraphQL Extra Options
This plugin was made to be a helper to the WPGraphQL plugin. Its purpose is to expose more of Wordpress API in a primarily read-only fashion for React/GraphQL or Vue/GraphQL theme development. Currently, it allows for the loading of options/settings not loaded through the Settings API to be accessed through GraphQL request, adds a ThemeMod Type to the schema, but currently is only works with the **next** & **feature/callStatic-added-to-Types-class** branches of my fork of the **WPGraphQL** repo, 

## Quick Install
Clone repository or zipped master to wordpress plugin directory and activate.

## Usage 
Upon activation navigate to "WPGraphQL Extra Options" under Settings.

## Custom Options/Settings Usage 
The plugin assumes the option can be retrieved through the `get_option` wordpress function.
Enter desired option in `option_name<->option_type<->option_description(optional)` format. Each option is to be separated by a new line. 

### Options/Settings Example

```
page_on_front<->integer<->id of static page used as homepage
page_for_posts<->integer<->id of page displaying blog posts
```

### GraphQL Request Example
All selected settings will appear under the `allSettings` type in camelCase.

```
{
  allSettings{
    pageOnFront
    pageForPosts
  }
}
```

## ThemeMods Usage
Theme have to loaded in by currently selected theme otherwise they aren't loaded to the schema.

## ThemeMods Exclude Example

```
nav_menu_locations
```

## GraphQL Request Example
All loaded theme_mods are location under `themeMods` in camelCase. They are also all returned as strings due to the natural of the get_theme_mods function. The function by default seems to return `string`, `string[]`, `null` or whatever is set to the default value. That being the case the query results with return either a `string` or a json encoded `string` for `string[]`.

```
{
  themeMods {
    navMenuLocations
  }
}
```

## TODO

### General
1. Add Testing
2. Add Documentation
3. Polish up user controls.

### ThemeMods
1. Add custom variable type functionality to schema implementation.
2. Add mutations