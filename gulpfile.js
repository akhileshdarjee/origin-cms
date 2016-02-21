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
		'../../../public/css/bootstrap.css',
		'../../../public/css/font-awesome.min.css',
		'../../../public/css/plugin.css',
		'../../../public/css/style.css',
		'../../../public/css/web_app/web_app.css'
	]);
});

elixir(function(mix) {
	mix.scripts([
		'../../../public/js/jquery-ui.min.js',
		'../../../public/js/bootstrap.js',
		'../../../public/js/file-input/bootstrap.file-input.js',
		'../../../public/js/bootstrap-typeahead.js',
		'../../../public/js/bootstrap-datepicker.js',
		'../../../public/js/moment.js',
		'../../../public/js/app.js',
		'../../../public/js/app.plugin.js',
		'../../../public/js/web_app/web_app.js'
	]);
});

elixir(function(mix) {
	mix.version(['css/all.css', 'js/all.js']);
});