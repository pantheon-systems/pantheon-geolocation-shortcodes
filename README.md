# Pantheon Geolocation Shortcodes

Used with the [Pantheon WordPress Edge Integrations SDK](https://github.com/pantheon-systems/edge-integrations-wordpress-sdk), this plugin allows sites to use shortcodes to display geolocated content.

[![Unsupported](https://img.shields.io/badge/pantheon-unsupported-yellow?logo=pantheon&color=FFDC28)](https://github.com/topics/unsupported?q=org%3Apantheon-systems "Unsupported, e.g. a tool we are actively using internally and are making available, but do not promise to support") ![Pantheon Geolocation Shortcodes](https://github.com/pantheon-systems/pantheon-geolocation-shortcodes/actions/workflows/main.yml/badge.svg)
## Installation

### Install via Composer
The recommended way to install the plugin is via Composer.

```bash
composer require pantheon-systems/pantheon-geolocation-shortcodes
```

The package is set as a `wordpress-plugin` so it should be installed alongside other WordPress plugins in your project.
### Install manually
The plugin can also be installed manually like a normal plugin. Find the latest release on the [Releases](https://github.com/pantheon-systems/pantheon-geolocation-shortcodes/releases) page and download the ZIP file.

Extract the contents of the ZIP file into your `wp-content/plugins` directory and upload or commit to your server.
### Requirements
This plugin does _not_ install the Pantheon WordPress Edge Integrations SDK or the [Pantheon WordPress Edge Integrations plugin](https://github.com/pantheon-systems/pantheon-wordpress-edge-integrations). Those must be installed separately. While the SDK is not explicitly required as a dependency, the WordPress Edge Integrations plugin _is_ required for the Geolocation plugin to function.

Addititionally, Pantheon Advanced Global CDN must be active and configured for Geolocation shortcodes to work.

Any other plugins that use the `[geoip]` shortcode should be uninstalled or deactivated before using Pantheon Geolocation Shortcodes.

## Updating
Updating the plugin depends on how you installed it. If you installed it via Composer, you can update it via Composer depending on your version constraints. See [Versions and constraints](https://getcomposer.org/doc/articles/versions.md) for more information.

If you installed the plugin manually, you can update it by downloading the latest release on the [Releases](https://github.com/pantheon-systems/pantheon-geolocation-shortcodes/releases) page and extracting the contents of the ZIP file into your `wp-content/plugins` directory.

## Usage

You can use the following shortcodes to display geolocated content based on where the visitor is located:

1. **Continent:** `[geoip-continent]`, the two-letter continent code, e.g. "NA"
2. **Country:** `[geoip-country]`, the two-letter country code, e.g. "US"
3. **Region:** `[geoip-region]`, a two-letter region code, e.g. "CA"
4. **City:** `[geoip-city]`, the full city name, e.g. "San Francisco"
5. **Location:** `[geoip-location]`, a combination of city, region, country, if all information is available, e.g. "San Francisco, CA, US"

### Localized content
In addition, you can also use the `geoip-content` shortcode to apply logic to content within your posts. The `geoip-content` shortcode can be used with any of the above parameters as well as allowing the ability to explicitly _exclude_ locations. Below are the available options that can be passed to the `geoip-content` shortcode:

* `continent`
* `country`
* `region`
* `city`
* `not_continent`
* `not_country`
* `not_region`
* `not_city`

#### Examples
Say you want to show content just to your US visitors. You can use the following shortcode:

```
[geoip-content country="US"]This is content just for US visitors.[/geoip-content]
```

Perhaps you just want to show content to visitors from specific regions. You can use the following shortcode:

```
[geoip-content region="CA, TX"]This is content exclusively for visitors from California and Texas.[/geoip-content]
```

You can also mix and match geography and negative geography options to add more complex logic. For example, if you wanted to show content to visitors from California and Texas, but not to visitors from Los Angeles. You can use the following shortcode:

```
[geoip-content region="CA, TX" not_city="Los Angeles"]This is content for visitors from California and Texas, but not from Los Angeles.[/geoip-content]
```

#### Duplicate location names
If multiple locations in your regions have the same name, you may need to adjust the logic to ensure that the correct content is shown to the correct visitors. For example, if you were showing the same content to visitors from the United States, Europe and Australia, but wanted to exclude _Dublin_, Ireland, you would need to use the following shortcodes:
```
[geoip-content country="US, AU"]Fly to Dublin, Ireland for a weekend getaway for only $199![/geoip-content][geoip-content country="EU" not_city="Dublin"]Fly to Dublin, Ireland for a weekend getaway for only $199![/geoip-content]
```