=== Yabe Webfont ===
Contributors: suabahasa
Donate link: https://ko-fi.com/suabahasa
Tags: custom fonts, google fonts, adobe fonts, self-hosting, performance, gdpr, bricks, oxygen, elementor, cwicly, zion builder, classic editor, beaver builder, generatepress, kadence, greenshift, breakdance, divi
Requires at least: 6.0
Tested up to: 6.2
Stable tag: 2.0.25
Requires PHP: 7.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Custom fonts management and self-host Google Fonts with seamless WordPress page builders integration

== Description ==

Yabe Webfont helps you use custom fonts on WordPress and easily self-host the Google Fonts.

Leverage Browser Cache, reduce DNS lookups/requests, reduce Cumulative Layout Shift and make your Google Fonts 100% GDPR compliant.

### GDPR Compliant

The European Union's General Data Protection Regulation (GDPR) is a regulation in EU law on data protection and privacy for all individuals within the European Union.
When using a Google Font on your website, you are required to get to get the consent from visitors before displaying the font.
You can avoid this by self-hosting the font on your website and not need to show your website visitors the GDPR-compliant cookie consent banner.

### Performance

Serving the font file from your own server will improve the website performance by reducing DNS lookups/requests and leverage Browser Cache.
It will also reduce the Cumulative Layout Shift (CLS) and improve the Core Web Vitals.

### User friendly

The plugin is designed to be user friendly and easy to use. It just require a few clicks!

- **Custom Fonts** — Upload your custom fonts and use them on your WordPress website.
- **Google Fonts Import** — Type on the search box to find the Google Fonts you want to self-host, then click the import button.
- **Adobe Fonts** — Add the project/kit ID to use the Adobe Fonts on your WordPress website.

### Seamless integration with page builders

Yabe Webfont is designed to be seamlessly integrated with the most popular page builders.

- [Gutenberg](https://wordpress.org/gutenberg)
- [Bricks](https://bricksbuilder.io/)
- [Oxygen](https://oxygenbuilder.com/)
- [Elementor](https://be.elementor.com/visit/?bta=209150&brand=elementor)
- [Divi](https://www.elegantthemes.com/affiliates/idevaffiliate.php?id=47622)
- [GeneratePress](https://generatepress.com/?ref=7954)
- [Cwicly](https://cwicly.com/?ref=suabahasa)
- [Breakdance](https://breakdance.com/ref/165/)
- [Zion Builder](https://zionbuilder.io/)
- [Builderius](https://builderius.io/)
- [Kadence WP](https://kadencewp.com)
- [Beaver Builder](https://www.wpbeaverbuilder.com/)
- [Greenshift](https://greenshiftwp.com/)
- [Spectra](https://wpspectra.com/)
- [Classic Editor](https://wordpress.org/plugins/classic-editor/)
- [FunnelKit/SlingBlocks](https://wordpress.org/plugins/slingblocks/)

== Changelog ==

= 2.0.25 =
* **[GeneratePress] Fix**: Google Fonts not disabled properly

= 2.0.24 =
* **New**: The website has been redesigned, and the documentation is now available at [webfont.yabe.land](https://webfont.yabe.land)
* **New**: [FunnelKit/SlingBlocks](https://wordpress.org/plugins/slingblocks/) integration
* **Improve**: Performance optimization
* **[Beaver Builder] Improve**: Disable built-in Google Fonts programatically
* **[Breakdance] Improve**: Ensure compatibility with Breakdance 1.3.0 and later
* **[Cwicly] Change**: Register the font using the new font system introduced in Cwicly [1.2.9.5](https://discourse.cwicly.com/t/1-2-9-5/2563)
* **[GeneratePress] New**: GenerateBlocks 1.8.0 and later integration
* **[Zion Builder] Fix**: Font list not showing on the visual editor

= 2.0.23 =
* **New**: Export and import
* **[Builderius] Improve**: Ensure compatibility with Builderius 0.11.0 and later

= 2.0.22 =
* **[Breakdance] Fix**: CSS file loaded twice

= 2.0.21 =
* **New**: [Builderius](https://builderius.io/) integration

= 2.0.20 =
* **New**: [Spectra](https://wpspectra.com/) integration
* **New**: Search Google Fonts by category with the `:` prefix. For example, `:sans-serif`  will search for all fonts in the sans-serif category
* **New**: CSS custom properties (variables) with pattern `--ywf--family-{font-slug}` for each family
* **New**: Allow to define fallback font
* **[Breakdance] New**: Fallback font support
* **[Bricks] New**: Fallback font support
* **[Gutenberg] New**: Fallback font support

= 2.0.19 =
* **Improve**: The font files URL now is a relative path, ensure working between environments
* **[Gutenberg] Fix**: Site Editor compatibility

= 2.0.18 =
* **New**: Adobe Fonts integration
* **Improve**: Database query optimization

= 2.0.17 =
* **[Bricks] Improve**: Ensure compatibility with Bricks 1.7.1 and later

= 2.0.16 =
* **Improve**: Regenerate the font files url with the new attachment url
* **Fix**: Import Google Fonts form is not resetting properly after successful import
* **[Bricks] Improve**: Font items on the Bricks editor is now grouped under the `Yabe Webfont` category

= 2.0.15 =
* **New**: Hide font files from Media Library

= 2.0.14 =
* **Improve**: IIS server compatibility

= 2.0.13 =
* **Improve**: Font' variants sort order
* **Improve**: IIS server compatibility

= 2.0.12 =
* **New**: Bulk file upload and auto organize the font files to the matching variants
* **Improve**: Bulk select variants on the Google Fonts import page
* **Fix**: Setting doesn't saved properly on the plugin settings page

= 2.0.11 =
* **New**: [Kadence WP](https://kadencewp.com) integration

= 2.0.10 =
* **Improve**: Fix compatibility with Blisk browser

= 2.0.9 =
* **[GeneratePress] Improve**: Register fonts on the Theme Customize page

= 2.0.8 =
* **New**: [Divi](https://www.elegantthemes.com/affiliates/idevaffiliate.php?id=47622) integration

= 2.0.7 =
* **[Oxygen] Fix**: Gutenberg editor compatibility

= 2.0.6 =
* **New**: Option of cache loading method, `file` or `inline`
* **Improve**: Preload the font files

= 2.0.5 =
* **Improve**: Sync the cache generation
* **Improve**: Add submenu to all page builders integration
* **Fix**: Issue with the plugin upgrade for the upcoming version
* **Fix**: Revert the admin notices style to WordPress default
* **[Bricks] Improve**: Force disable the built-in Google Fonts and override the `Bricks > Settings > Performance: Disable Google Fonts` setting
* **[Elementor] Improve**: Force disable the built-in Google Fonts and override the `Elementor > Settings > Advanced: Google Fonts` setting
* **[Gutenberg] Improve**: Support non block-based theme
* **[Oxygen] Improve**: Force disable the built-in Google Fonts and override the `Oxygen > Settings > Bloat Eliminator: Disable Google Fonts` setting

= 2.0.4 =
* **New**: [Greenshift](https://greenshiftwp.com/) integration
* **Improve**: Better variable fonts support for Google Fonts
* **Improve**: Delete all fonts file associated with the deleted font
* **Fix**: Scheduled cache generation not cleared properly on manual action
* **[Oxygen] Improve**: Disable Elegant Custom Fonts plugin

= 2.0.3 =
* **New**: [Breakdance](https://breakdance.com/ref/165/) integration
* **[Gutenberg] Fix**: Font CSS not loaded on the FSE editor

= 2.0.2 =
* **Improve**: Tweak the plugin interface
* **Improve**: Support width axes for variable fonts
* **[Zion Builder] Fix**: Font family list not showing up on the builder editor

= 2.0.1 =
* **Improve**: Lower the minimum PHP version to 7.4

= 2.0.0 =
* **New**: Plugin rebuilt from scratch
* **New**: New plugin interface
* **New**: Custom fonts management
* **New**: Editable imported Google Fonts
* **Fix**: Some Google Fonts with variable fonts are not loaded properly
* **Change**: Plugin admin menu moved to `Appearance → Yabe Webfont`
* **New**: [Beaver Builder](https://www.wpbeaverbuilder.com/) integration
* **New**: [Bricks](https://bricksbuilder.io/) integration
* **New**: [Cwicly](https://cwicly.com/?ref=suabahasa) integration
* **New**: [Classic Editor](https://wordpress.org/plugins/classic-editor/) integration
* **New**: [Elementor](https://be.elementor.com/visit/?bta=209150&brand=elementor) integration
* **New**: [GeneratePress](https://generatepress.com/?ref=7954) integration
* **New**: [Gutenberg](https://wordpress.org/gutenberg) integration
* **New**: [Oxygen](https://oxygenbuilder.com/) integration
* **New**: [Zion Builder](https://zionbuilder.io/) integration

= 1.0.0 =
* 🐣 Initial release.