jQuery(document).ready(function($) {
    $('#copy-json').on('click', function() {
        const textarea = document.getElementById('composer-json');
        textarea.select();
        document.execCommand('copy');
        
        const $button = $(this);
        const originalText = $button.text();
        
        $button.text('Copied!');
        setTimeout(function() {
            $button.text(originalText);
        }, 2000);
    });
}); 