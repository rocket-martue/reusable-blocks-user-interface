# Reusable Blocks User Interface

Requires at least: 5.7

Tested up to: 6.6

Requires PHP: 7.3

License: GPLv2 or later

License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin adds "Patterns" to the admin menu for easy editing. It also allows you to easily insert blocks into your posts using a shortcode.

## Changelog

### Version 1.0.8
- **Security & Performance Improvements:**
  - Replaced automatic file loading with manual require_once statements
  - Fixed open_basedir restriction errors on shared hosting environments
  - Improved code predictability and debugging capabilities
  - Enhanced security by explicitly controlling which files are loaded

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

As of version 1.0.8, the plugin uses manual file loading for better security and performance:

```php
require_once RBUI_PATH . '/functions/rbui-admin-menu.php';
require_once RBUI_PATH . '/functions/rbui-functions.php'; 
require_once RBUI_PATH . '/functions/rbui-shortcode.php';
require_once RBUI_PATH . '/functions/class-rbui-widget.php';
```

**Benefits of manual loading:**
- Better security (only intended files are loaded)
- No open_basedir restriction issues
- Improved performance (no directory scanning)
- Easier debugging and maintenance
- More predictable behavior

**Adding new functionality files:**
When adding new PHP files to the `functions/` directory, you must manually add a `require_once` statement to the main plugin file.
