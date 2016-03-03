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

	'tabUser' => [
		'link_field' => 'login_id',
		'search_via' => 'login_id',
		'cols' => ['login_id', 'full_name', 'role', 'status']
	],

];