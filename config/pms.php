<?php

return [
	'control_number' => [
		'initial' => env('CONTROL_NUMBER_INITIAL', '21'),
		'customer' => env('CUSTOMER_CHARGEABLE_CODE', '0'),
		'client' => env('CLIENT_CHARGEABLE_CODE', '1'),
	],
];