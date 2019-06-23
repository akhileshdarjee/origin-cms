let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/* Origin CMS Web App */

mix.styles([
    'resources/assets/css/jquery-ui.min.css',
    'resources/assets/css/bootstrap.min.css',
    'resources/assets/css/font-awesome.min.css',
    'resources/assets/plugins/toastr/toastr.min.css',
    'resources/assets/plugins/datetimepicker/bootstrap-datetimepicker.min.css',
    'resources/assets/plugins/trumbowyg/trumbowyg.min.css',
    'resources/assets/plugins/trumbowyg/trumbowyg.colors.min.css',
    'resources/assets/css/AdminLTE.min.css',
    'resources/assets/css/skins/_all-skins.min.css',
    'resources/assets/css/origin/origin.css',
], 'public/css/all.css').version();

mix.scripts([
    'resources/assets/js/jquery-3.1.1.min.js',
    'resources/assets/js/jquery-ui.min.js',
    'resources/assets/js/jquery.highlight.js',
    'resources/assets/js/bootstrap.min.js',
    'resources/assets/plugins/slimScroll/jquery.slimscroll.min.js',
    'resources/assets/plugins/toastr/toastr.min.js',
    'resources/assets/plugins/moment/moment.js',
    'resources/assets/plugins/datetimepicker/bootstrap-datetimepicker.min.js',
    'resources/assets/plugins/trumbowyg/trumbowyg.min.js',
    'resources/assets/plugins/trumbowyg/plugins/upload/trumbowyg.upload.min.js',
    'resources/assets/plugins/trumbowyg/plugins/colors/trumbowyg.colors.min.js',
    'resources/assets/plugins/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js',
    'resources/assets/plugins/trumbowyg/plugins/table/trumbowyg.table.min.js',
    'resources/assets/js/app.min.js',
    'resources/assets/js/origin/origin.js',
    'resources/assets/js/webfontloader.js',
], 'public/js/all.js').version();

/* Origin CMS Activity */

mix.scripts([
    'resources/assets/js/origin/activity.js'
], 'public/js/origin_activity.js').version();

/* Origin CMS Backups */

mix.scripts([
    'resources/assets/js/origin/backups.js'
], 'public/js/origin_backups.js').version();

/* Origin CMS List View */

mix.scripts([
    'resources/assets/js/origin/list_view.js'
], 'public/js/origin_list_view.js').version();

/* Origin CMS Form View */

mix.scripts([
    'resources/assets/js/origin/form.js',
    'resources/assets/js/origin/table.js'
], 'public/js/origin_form.js').version();

/* Origin CMS Reports */

mix.styles([
    'resources/assets/plugins/datatables/dataTables.bootstrap.css',
], 'public/css/origin_report.css').version();

mix.scripts([
    'resources/assets/plugins/datatables/jquery.dataTables.min.js',
    'resources/assets/plugins/datatables/dataTables.bootstrap.min.js',
    'resources/assets/js/origin/report_view.js'
], 'public/js/origin_report.js').version();
