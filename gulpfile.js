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
		'../../../public/css/bootstrap.css',
		'../../../public/css/font-awesome.min.css',
		'../../../public/css/plugins/toastr/toastr.min.css',
		'../../../public/js/plugins/gritter/jquery.gritter.css',
		'../../../public/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css',
		'../../../public/css/plugins/datapicker/datepicker3.css',
		'../../../public/css/bootstrap-datetimepicker.css',
		'../../../public/css/animate.css',
		'../../../public/css/style.css',
		'../../../public/css/web_app/web_app.css'
	]);
});

elixir(function(mix) {
	mix.scripts([
		'../../../public/js/jquery-ui.min.js',
		'../../../public/js/bootstrap.js',
		'../../../public/js/plugins/metisMenu/jquery.metisMenu.js',
		'../../../public/js/plugins/slimscroll/jquery.slimscroll.min.js',
		'../../../public/js/inspinia.js',
		'../../../public/js/plugins/pace/pace.min.js',
		'../../../public/js/plugins/peity/jquery.peity.min.js',
		'../../../public/js/plugins/gritter/jquery.gritter.min.js',
		'../../../public/js/plugins/sparkline/jquery.sparkline.min.js',
		'../../../public/js/plugins/chartJs/Chart.min.js',
		'../../../public/js/plugins/toastr/toastr.min.js',
		'../../../public/js/bootstrap-typeahead.js',
		'../../../public/js/moment.js',
		'../../../public/js/plugins/datapicker/bootstrap-datepicker.js',
		'../../../public/js/bootstrap-datetimepicker.js',
		'../../../public/js/web_app/web_app.js'
	]);
});

elixir(function(mix) {
	mix.version(['css/all.css', 'js/all.js']);
});