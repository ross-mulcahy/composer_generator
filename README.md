# Composer Generator for WordPress

Generate composer.json files for your WordPress plugins automatically. This plugin helps you manage your WordPress plugins using Composer by creating a properly formatted composer.json file based on your currently installed plugins.

## Features

- Automatically detects installed plugins
- Generates a properly formatted composer.json file
- Verifies plugin availability on WPackagist
- Easy one-click copy to clipboard
- Clear installation instructions
- Compatible with WordPress Multisite

## Installation

1. Download the plugin
2. Upload to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Access the tool via Tools > Composer in your WordPress admin

## Usage

1. Navigate to Tools > Composer in your WordPress admin panel
2. Click "Generate composer.json" to create your composer configuration
3. Copy the generated JSON to a file named `composer.json` in your wp-content directory
4. Run `composer update` in your terminal
5. If any packages are flagged as missing, remove them using:
   ```bash
   composer remove wpackagist-plugin/plugin-name
   ```
6. Run `composer update` again

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- [Composer](https://getcomposer.org/) installed on your server
- Write access to your wp-content directory

## How It Works

The plugin:
1. Scans your WordPress installation for active plugins
2. Checks each plugin against the WPackagist repository
3. Generates a composer.json file with proper version constraints
4. Includes necessary repository configurations for WPackagist
5. Sets up proper installer paths for WordPress plugins

## Common Issues

### Missing Plugins
Some plugins might not be available on WPackagist. These will need to be managed manually or through alternative means.

### Version Conflicts
If you encounter version conflicts:
1. Check the specific version requirements
2. Update your plugins to compatible versions
3. Remove problematic plugins from composer.json if necessary

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the GPL v3 or later.

## Credits

- [Composer](https://getcomposer.org/) - Dependency Manager for PHP
- [WPackagist](https://wpackagist.org/) - WordPress Plugin Directory as a Composer Repository

## Author

Ross Mulcahy

## Changelog

### 1.1.1
- Added comprehensive documentation
- Improved code organization
- Enhanced UI with wider cards
- Added better code comments

### 1.1.0
- Added copy to clipboard functionality
- Improved error handling
- Enhanced UI styling

### 1.0.0
- Initial release
- Basic composer.json generation
- Plugin detection functionality
