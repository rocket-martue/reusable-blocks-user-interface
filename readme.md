# Reusable Blocks User Interface

Requires at least: 5.7

Tested up to: 6.6

Requires PHP: 7.3

License: GPLv2 or later

License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin adds "Patterns" to the admin menu for easy editing. It also allows you to easily insert blocks into your posts using a shortcode.

## Changelog

### Version 1.0.7
- **File Structure Changes:**
  - Renamed `functions/rbui-widget.php` → `functions/class-rbui-widget.php` for better organization
  - Updated file loading system to exclude files starting with hyphen (`-`) instead of underscore (`_`)
- **Code Improvements:**
  - Added comprehensive code comments for better maintainability
  - Updated translation files (POT/PO) to match current code structure
- **Documentation:**
  - Enhanced README with development notes and file loading system explanation

### Version 1.0.6
- Previous stable release

## Development Notes

### File Loading System

The plugin automatically loads all PHP files from the `functions/` directory. To exclude files from being loaded, prefix the filename with a hyphen (`-`). This is useful for:

- Development files
- Test files  
- Backup files
- Disabled functionality

Example:
- `functions/rbui-admin-menu.php` → Will be loaded
- `functions/-disabled-feature.php` → Will NOT be loaded
