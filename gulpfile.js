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
		'../../../public/vendors/nprogress/nprogress.css',
		'../../../public/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css',
		'../../../public/vendors/normalize-css/normalize.css',
		'../../../public/css/custom.min.css',
		'../../../public/css/web_app/web_app.css'
	]);
});

elixir(function(mix) {
	mix.scripts([
		'../../../public/js/jquery-ui.min.js',
		'../../../public/js/bootstrap.min.js',
		'../../../public/vendors/fastclick/lib/fastclick.js',
		'../../../public/js/bootstrap-typeahead.js',
		'../../../public/js/moment.js',
		'../../../public/vendors/nprogress/nprogress.js',
		'../../../public/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
		'../../../public/js/bootstrap-datetimepicker.js',
		'../../../public/js/custom.min.js',
		'../../../public/js/web_app/web_app.js'
	]);
});

elixir(function(mix) {
	mix.styles([
		'../../../public/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
		'../../../public/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',
		'../../../public/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css',
		'../../../public/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css',
		'../../../public/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css'
	], 'public/css/web_app/app-report.css');
});

elixir(function(mix) {
	mix.scripts([
		'../../../public/vendors/datatables.net/js/jquery.dataTables.min.js',
		'../../../public/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
		'../../../public/vendors/datatables.net-buttons/js/dataTables.buttons.min.js',
		'../../../public/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',
		'../../../public/vendors/datatables.net-buttons/js/buttons.flash.min.js',
		'../../../public/vendors/datatables.net-buttons/js/buttons.html5.min.js',
		'../../../public/vendors/datatables.net-buttons/js/buttons.print.min.js',
		'../../../public/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js',
		'../../../public/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js',
		'../../../public/vendors/datatables.net-responsive/js/dataTables.responsive.min.js',
		'../../../public/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js',
		'../../../public/vendors/datatables.net-scroller/js/dataTables.scroller.min.js',
		'../../../public/js/web_app/report_view.js'
	], 'public/js/web_app/app-report.js');
});

elixir(function(mix) {
	mix.version(['css/all.css', 'js/all.js', 'css/web_app/app-report.css', 'js/web_app/app-report.js']);
});