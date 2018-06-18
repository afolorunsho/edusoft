<?php

return [
	'driver' => env('MAIL_DRIVER', 'smtp'),
	'host' =>env('MAIL_HOST', 'smtp.gmail.com'),
	'port' =>env('MAIL_PORT', 587),
	'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'edusoftemail@gmail.com'),
        'name' => env('MAIL_FROM_NAME', 'EduSoft Email Server'),
    ],
	'encryption' => 'tls',
	'username' => env('MAIL_USERNAME', 'edusoftemail@gmail.com'),
	'password' => env('MAIL_PASSWORD', 'Rc1052258'),
	'sendmail' => '/usr/sbin/sendmail -t',
	'pretend' => false,
    
];
