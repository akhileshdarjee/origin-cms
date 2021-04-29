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
    'resources/plugins/fontawesome-free/css/all.min.css',
    'resources/plugins/datetimepicker/bootstrap-datetimepicker.min.css',
    'resources/plugins/trumbowyg/trumbowyg.min.css',
    'resources/plugins/trumbowyg/trumbowyg.colors.min.css',
    'resources/css/adminlte.min.css',
    'resources/css/origin/origin.css',
], 'public/css/all.css').version();

mix.scripts([
    'resources/plugins/jquery/jquery.min.js',
    'resources/plugins/jquery-ui/jquery-ui.min.js',
    'resources/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'resources/plugins/moment/moment-with-locales.min.js',
    'resources/plugins/moment/moment-timezone-with-data.min.js',
    'resources/plugins/datetimepicker/bootstrap-datetimepicker.min.js',
    'resources/plugins/trumbowyg/trumbowyg.min.js',
    'resources/plugins/trumbowyg/plugins/upload/trumbowyg.upload.min.js',
    'resources/plugins/trumbowyg/plugins/colors/trumbowyg.colors.min.js',
    'resources/plugins/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js',
    'resources/plugins/trumbowyg/plugins/table/trumbowyg.table.min.js',
    'resources/js/adminlte.min.js',
    'resources/js/origin/origin.js',
    'resources/js/webfontloader.js',
], 'public/js/all.js').version();

/* Origin CMS Activity */

mix.scripts([
    'resources/js/origin/activity.js',
], 'public/js/origin/activity.js').version();

/* Origin CMS Backups */

mix.scripts([
    'resources/js/origin/backups.js',
], 'public/js/origin/backups.js').version();

/* Origin CMS List View */

mix.scripts([
    'resources/js/origin/list_view.js',
], 'public/js/origin/list_view.js').version();

/* Origin CMS Form View */

mix.scripts([
    'resources/js/origin/form.js',
    'resources/js/origin/table.js',
], 'public/js/origin/form.js').version();

/* Origin CMS Reports */

mix.styles([
    'resources/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
    'resources/plugins/datatables-responsive/css/responsive.bootstrap4.min.css',
    'resources/css/origin/report_view.css',
], 'public/css/origin/report_view.css').version();

mix.scripts([
    'resources/plugins/datatables/jquery.dataTables.min.js',
    'resources/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
    'resources/plugins/datatables-responsive/js/dataTables.responsive.min.js',
    'resources/plugins/datatables-responsive/js/responsive.bootstrap4.min.js',
    'resources/js/origin/report_view.js',
], 'public/js/origin/report_view.js').version();
