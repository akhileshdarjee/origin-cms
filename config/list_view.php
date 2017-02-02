<?php

return [

	/*
	|--------------------------------------------------------------------------
	| List view config to show
	|--------------------------------------------------------------------------
	|
	| Contains columns, link field and search filter field for list view
	|
	*/

	'tabReports' => [
		'link_field' => 'id',
		'search_via' => 'name',
		'cols' => ['name', 'type', 'module', 'status']
	],
	'tabUser' => [
		'link_field' => 'id',
		'search_via' => 'login_id',
		'cols' => ['login_id', 'full_name', 'role', 'status']
	],

];