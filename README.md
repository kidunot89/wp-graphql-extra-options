# WPGraphQL Extra Options
This plugin was made to be a temporary helper to the WPGraphQL plugin. It allows for the addition of settings not loaded through the Settings API to be accessed through GraphQL request.

## Quick Install
Clone repository or zipped master to wordpress plugin directory and activate.

## Usage 
Upon activation navigate to "WPGraphQL Extra Options" under Settings.

Enter desired option in `option_name<->option_type<->option_description(optional)` format. Each option is to be separated by a new line. 

## Settings Example

```
page_on_front<->integer<->id of static page used as homepage
page_for_posts<->integer<->id of page displaying blog posts
```

## GraphQL Request Example
All selected setting will under the `allSettings` type in camelCase.

```
{
  allSettings{
    pageOnFront
    pageForPosts
  }
}

```