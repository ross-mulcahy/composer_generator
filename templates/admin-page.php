<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h1><?php esc_html_e('Composer Generator', 'composer-generator'); ?></h1>

    <div class="card">
        <h2><?php esc_html_e('Instructions', 'composer-generator'); ?></h2>
        <ol>
            <li><?php esc_html_e('Make sure', 'composer-generator'); ?> <a href="https://getcomposer.org/" target="_blank" rel="noopener noreferrer">Composer</a> <?php esc_html_e('is installed.', 'composer-generator'); ?></li>
            <li><?php esc_html_e('Copy the generated JSON into a file called composer.json within the wp-content folder.', 'composer-generator'); ?></li>
            <li><?php esc_html_e('Run', 'composer-generator'); ?> <code>composer update</code></li>
            <li><?php esc_html_e('If any missing packages are flagged, remove them with', 'composer-generator'); ?> <code>composer remove wpackagist-plugin/plugin-name</code> <?php esc_html_e('and run', 'composer-generator'); ?> <code>composer update</code> <?php esc_html_e('again.', 'composer-generator'); ?></li>
        </ol>
    </div>

    <div class="card">
        <h2><?php esc_html_e('Generated composer.json', 'composer-generator'); ?></h2>
        <textarea id="composer-json" readonly style="width: 100%; height: 500px; font-family: monospace; font-size: 13px; white-space: pre; overflow: auto;"><?php echo esc_textarea($this->get_active_plugins_composer_json()); ?></textarea>
        <p>
            <button type="button" class="button button-primary" id="copy-json"><?php esc_html_e('Copy to Clipboard', 'composer-generator'); ?></button>
        </p>
    </div>

    <p class="description">
        <?php esc_html_e('Powered by', 'composer-generator'); ?> 
        <a href="https://getcomposer.org/" target="_blank" rel="noopener noreferrer">Composer</a> 
        <?php esc_html_e('and', 'composer-generator'); ?> 
        <a href="https://wpackagist.org/" target="_blank" rel="noopener noreferrer">WordPress Packagist</a>
    </p>
</div> 