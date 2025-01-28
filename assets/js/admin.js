/**
 * Admin JavaScript for Composer Generator
 * Handles clipboard functionality for the generated composer.json
 */
jQuery(document).ready(function($) {
    // Handle click event for the copy button
    $('#copy-json').on('click', function() {
        // Select the textarea content
        const textarea = document.getElementById('composer-json');
        textarea.select();
        
        // Copy to clipboard
        document.execCommand('copy');
        
        // Update button text to provide feedback
        const $button = $(this);
        const originalText = $button.text();
        
        $button.text('Copied!');
        
        // Reset button text after 2 seconds
        setTimeout(function() {
            $button.text(originalText);
        }, 2000);
    });
}); 