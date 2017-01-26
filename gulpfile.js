var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
	mix.sass('app.scss');
});

/* Backend Web App */

elixir(function(mix) {
	mix.styles([
		'../../../public/css/jquery-ui.min.css',
		'../../../public/css/bootstrap.min.css',
		'../../../public/css/font-awesome.min.css',
		'../../../public/css/animate.css',
		'../../../public/css/bootstrap-datetimepicker.css',
		'../../../public/css/AdminLTE.min.css',
		'../../../public/css/skins/skin-blue.min.css',
		'../../../public/plugins/pace/pace.min.css'
	]);
});

elixir(function(mix) {
	mix.scripts([
		'../../../public/js/jquery-ui.min.js',
		'../../../public/js/bootstrap.min.js',
		'../../../public/plugins/slimScroll/jquery.slimscroll.min.js',
		'../../../public/plugins/pace/pace.min.js',
		'../../../public/js/bootstrap-typeahead.js',
		'../../../public/js/moment.js',
		'../../../public/js/bootstrap-datetimepicker.js',
		'../../../public/js/tinymce/tinymce.min.js',
		'../../../public/js/app.min.js',
		'../../../public/js/web_app/web_app.js'
	]);
});

elixir(function(mix) {
	mix.styles([
		'../../../public/plugins/datatables/dataTables.bootstrap.css',
	], 'public/css/web_app/app-report.css');
});

elixir(function(mix) {
	mix.scripts([
		'../../../public/plugins/datatables/jquery.dataTables.min.js',
		'../../../public/plugins/datatables/dataTables.bootstrap.min.js',
		'../../../public/js/web_app/report_view.js'
	], 'public/js/web_app/app-report.js');
});

/* Versioning */

elixir(function(mix) {
	mix.version(['css/all.css', 'js/all.js', 'css/web_app/app-report.css', 'js/web_app/app-report.js']);
});