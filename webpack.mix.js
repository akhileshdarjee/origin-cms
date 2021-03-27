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
    'resources/css/jquery-ui.min.css',
    'resources/css/bootstrap.min.css',
    'resources/css/font-awesome.min.css',
    'resources/plugins/toastr/toastr.min.css',
    'resources/plugins/datetimepicker/bootstrap-datetimepicker.min.css',
    'resources/plugins/trumbowyg/trumbowyg.min.css',
    'resources/plugins/trumbowyg/trumbowyg.colors.min.css',
    'resources/css/AdminLTE.min.css',
    'resources/css/skins/_all-skins.min.css',
    'resources/css/origin/origin.css',
], 'public/css/all.css').version();

mix.scripts([
    'resources/js/jquery-3.1.1.min.js',
    'resources/js/jquery-ui.min.js',
    'resources/js/jquery.highlight.js',
    'resources/js/bootstrap.min.js',
    'resources/plugins/slimScroll/jquery.slimscroll.min.js',
    'resources/plugins/toastr/toastr.min.js',
    'resources/plugins/moment/moment.js',
    'resources/plugins/datetimepicker/bootstrap-datetimepicker.min.js',
    'resources/plugins/trumbowyg/trumbowyg.min.js',
    'resources/plugins/trumbowyg/plugins/upload/trumbowyg.upload.min.js',
    'resources/plugins/trumbowyg/plugins/colors/trumbowyg.colors.min.js',
    'resources/plugins/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js',
    'resources/plugins/trumbowyg/plugins/table/trumbowyg.table.min.js',
    'resources/js/app.min.js',
    'resources/js/origin/origin.js',
    'resources/js/webfontloader.js',
], 'public/js/all.js').version();

/* Origin CMS Activity */

mix.scripts([
    'resources/js/origin/activity.js'
], 'public/js/origin/activity.js').version();

/* Origin CMS Backups */

mix.scripts([
    'resources/js/origin/backups.js'
], 'public/js/origin/backups.js').version();

/* Origin CMS List View */

mix.scripts([
    'resources/js/origin/list_view.js'
], 'public/js/origin/list_view.js').version();

/* Origin CMS Form View */

mix.scripts([
    'resources/js/origin/form.js',
    'resources/js/origin/table.js'
], 'public/js/origin/form.js').version();

/* Origin CMS Reports */

mix.styles([
    'resources/plugins/datatables/dataTables.bootstrap.css',
], 'public/css/origin/report_view.css').version();

mix.scripts([
    'resources/plugins/datatables/jquery.dataTables.min.js',
    'resources/plugins/datatables/dataTables.bootstrap.min.js',
    'resources/js/origin/report_view.js'
], 'public/js/origin/report_view.js').version();
