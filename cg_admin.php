<?php

/**
 * Plugin Name: Composer Generator
 * Description: Create composer.json file from current plugins available on WPackagist.org.
 * Author: Ross Mulcahy
 * Version: 1.1.1
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This plugin generates a composer.json file for WordPress installations,
 * making it easier to manage plugins through Composer package manager.
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants for easy reference
define('CG_VERSION', '1.1.1');
define('CG_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CG_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class using singleton pattern
 * Handles all core functionality of the Composer Generator plugin
 */
class ComposerGenerator {
    /** @var ComposerGenerator|null Singleton instance */
    private static $instance = null;

    /**
     * Get singleton instance of the plugin
     * @return ComposerGenerator
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Private constructor to prevent direct creation
     * Adds necessary WordPress hooks
     */
    private function __construct() {
        add_action('admin_menu', [$this, 'register_admin_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    /**
     * Registers the plugin's admin page under Tools menu
     */
    public function register_admin_page() {
        add_submenu_page(
            'tools.php',
            __('Composer', 'composer-generator'),
            __('Composer', 'composer-generator'),
            'manage_options',
            'composer-generator',
            [$this, 'render_admin_page']
        );
    }

    /**
     * Enqueues necessary JavaScript files for the admin page
     * @param string $hook Current admin page hook
     */
    public function enqueue_admin_scripts($hook) {
        if ('tools_page_composer-generator' !== $hook) {
            return;
        }
        wp_enqueue_script(
            'composer-generator-admin',
            CG_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            CG_VERSION,
            true
        );
        wp_localize_script('composer-generator-admin', 'cgAdmin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('composer_generator_nonce')
        ]);
    }

    /**
     * Renders the admin page template
     * Checks for proper permissions before rendering
     */
    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        require_once CG_PLUGIN_DIR . 'templates/admin-page.php';
    }

    /**
     * Generates the composer.json content as a JSON string
     * @return string Formatted JSON string
     */
    public function get_active_plugins_composer_json() {
        $composer_data = [
            'name' => 'wordpress/composer-plugins',
            'description' => 'WordPress plugins managed by Composer',
            'repositories' => [
                [
                    'type' => 'composer',
                    'url' => 'https://wpackagist.org',
                    'only' => ['wpackagist-plugin/*', 'wpackagist-theme/*']
                ]
            ],
            'require' => $this->get_plugin_requirements(),
            'config' => [
                'allow-plugins' => [
                    'composer/installers' => true
                ]
            ],
            'extra' => [
                'installer-paths' => [
                    'wp-content/plugins/{$name}/' => ['type:wordpress-plugin']
                ]
            ]
        ];

        return json_encode($composer_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Gets all active plugin requirements for composer.json
     * @return array Array of plugin requirements with versions
     */
    private function get_plugin_requirements() {
        $requirements = [];
        $all_plugins = get_plugins();

        foreach ($all_plugins as $plugin_file => $plugin_data) {
            if (empty($plugin_data['TextDomain'])) {
                continue;
            }

            $plugin_info = $this->get_plugin_info($plugin_data['TextDomain']);
            if ($plugin_info) {
                $requirements["wpackagist-plugin/{$plugin_data['TextDomain']}"] = ">={$plugin_data['Version']}";
            }
        }

        return $requirements;
    }

    /**
     * Retrieves plugin information from WordPress.org API
     * @param string $text_domain Plugin's text domain
     * @return object|false Plugin information or false if not found
     */
    private function get_plugin_info($text_domain) {
        require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
        
        try {
            $info = plugins_api('plugin_information', [
                'slug' => str_replace('/', '', basename($text_domain))
            ]);

            if (is_wp_error($info)) {
                return false;
            }

            return $info;
        } catch (Exception $e) {
            return false;
        }
    }
}

// Initialize the plugin when WordPress loads
function composer_generator_init() {
    ComposerGenerator::get_instance();
}
add_action('plugins_loaded', 'composer_generator_init');