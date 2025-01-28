<?php
/**
 * Admin page template for Composer Generator
 * Displays instructions and generates composer.json content
 *
 * @package ComposerGenerator
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}
?>
<!-- Custom styles for the admin interface -->
<style>
    /* Make cards wider than default WordPress cards */
    .composer-generator-wrap .card {
        max-width: 1200px; /* Default card is usually around 600px */
        margin-bottom: 20px;
        padding: 20px;
    }
    
    /* Ensure textarea fits within card boundaries */
    .composer-generator-wrap textarea {
        max-width: 100%;
        margin-top: 10px;
    }
</style>

<div class="wrap composer-generator-wrap">
    <h1><?php esc_html_e('Composer Generator', 'composer-generator'); ?></h1>

    <!-- Instructions card -->
    <div class="card">
        <h2><?php esc_html_e('Instructions', 'composer-generator'); ?></h2>
        <ol>
            <li><?php esc_html_e('Make sure', 'composer-generator'); ?> <a href="https://getcomposer.org/" target="_blank" rel="noopener noreferrer">Composer</a> <?php esc_html_e('is installed.', 'composer-generator'); ?></li>
            <li><?php esc_html_e('Copy the generated JSON into a file called composer.json within the wp-content folder.', 'composer-generator'); ?></li>
            <li><?php esc_html_e('Run', 'composer-generator'); ?> <code>composer update</code></li>
            <li><?php esc_html_e('If any missing packages are flagged, remove them with', 'composer-generator'); ?> <code>composer remove wpackagist-plugin/plugin-name</code> <?php esc_html_e('and run', 'composer-generator'); ?> <code>composer update</code> <?php esc_html_e('again.', 'composer-generator'); ?></li>
        </ol>
    </div>

    <!-- Generated JSON card -->
    <div class="card">
        <h2><?php esc_html_e('Generated composer.json', 'composer-generator'); ?></h2>
        <textarea id="composer-json" readonly style="width: 100%; height: 500px; font-family: monospace; font-size: 13px; white-space: pre; overflow: auto;"><?php echo esc_textarea($this->get_active_plugins_composer_json()); ?></textarea>
        <p>
            <button type="button" class="button button-primary" id="copy-json"><?php esc_html_e('Copy to Clipboard', 'composer-generator'); ?></button>
        </p>
    </div>

    <!-- Attribution footer -->
    <p class="description">
        <?php esc_html_e('Powered by', 'composer-generator'); ?> 
        <a href="https://getcomposer.org/" target="_blank" rel="noopener noreferrer">Composer</a> 
        <?php esc_html_e('and', 'composer-generator'); ?> 
        <a href="https://wpackagist.org/" target="_blank" rel="noopener noreferrer">WordPress Packagist</a>
    </p>
</div> 