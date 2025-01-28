<?php

/**
 * Plugin Name: Composer Generator
 * Description: Create composer.json file from current plugins available on WPackagist.org.
 * Author: Ross Mulcahy
 * Version: 1.1.0
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CG_VERSION', '1.1.0');
define('CG_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CG_PLUGIN_URL', plugin_dir_url(__FILE__));

class ComposerGenerator {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'register_admin_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

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

    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        require_once CG_PLUGIN_DIR . 'templates/admin-page.php';
    }

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

// Initialize the plugin
function composer_generator_init() {
    ComposerGenerator::get_instance();
}
add_action('plugins_loaded', 'composer_generator_init');