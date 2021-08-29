$(document).ready(function() {
    if ($('[name="id"]').val()) {
        var email_verified = '<span class="indicator-pill indicator-danger ml-2">' + __("No") + '</span>';

        if (origin.data[origin.table_name]['email_verified_at']) {
            var email_verified = '<span class="indicator-pill indicator-success ml-2">' + __("Yes") + '</span>';
        }

        addFormStatic(__("Email Verified"), email_verified);
    }

    $('body').on('change', '[name="title"]', function () {
        setFullName();
    });

    $('body').on('change', '[name="first_name"]', function () {
        setFullName();
    });

    $('body').on('change', '[name="last_name"]', function () {
        setFullName();
    });

    function setFullName() {
        var title = $('body').find('[name="title"]').val();
        var first_name = $.trim($('body').find('[name="first_name"]').val());
        var last_name = $.trim($('body').find('[name="last_name"]').val());
        var full_name = first_name;

        if (title) {
            full_name = title + ' ' + full_name;
        }

        if (last_name) {
            full_name = full_name + ' ' + last_name;
        }

        $('body').find('[name="full_name"]').val(full_name);
    }
});