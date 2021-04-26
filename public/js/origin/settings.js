$(document).ready(function() {
    $('body').on('change', '[name="theme"]', function() {
        applyTheme(trim($(this).val()));
    });
});