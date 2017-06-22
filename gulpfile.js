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
		'../../../public/plugins/datepicker/datepicker3.css',
		'../../../public/css/bootstrap-datetimepicker.css',
		'../../../public/css/AdminLTE.min.css',
		'../../../public/css/skins/skin-blue.min.css',
		'../../../public/css/origin/origin.css',
	]);
});

elixir(function(mix) {
	mix.scripts([
		'../../../public/js/jquery-ui.min.js',
		'../../../public/js/jquery.highlight.js',
		'../../../public/js/bootstrap.min.js',
		'../../../public/plugins/slimScroll/jquery.slimscroll.min.js',
		'../../../public/js/bootstrap-typeahead.js',
		'../../../public/js/moment.js',
		'../../../public/plugins/datepicker/bootstrap-datepicker.js',
		/* '../../../public/plugins/timepicker/bootstrap-timepicker.min.js', */
		'../../../public/js/bootstrap-datetimepicker.js',
		'../../../public/js/tinymce/tinymce.min.js',
		'../../../public/js/app.min.js',
		'../../../public/js/origin/origin.js'
	]);
});

elixir(function(mix) {
	mix.styles([
		'../../../public/plugins/datatables/dataTables.bootstrap.css',
	], 'public/css/origin/app-report.css');
});

elixir(function(mix) {
	mix.scripts([
		'../../../public/plugins/datatables/jquery.dataTables.min.js',
		'../../../public/plugins/datatables/dataTables.bootstrap.min.js',
		'../../../public/js/origin/report_view.js'
	], 'public/js/origin/app-report.js');
});

/* Versioning */

elixir(function(mix) {
	mix.version(['css/all.css', 'js/all.js', 'css/origin/app-report.css', 'js/origin/app-report.js']);
});