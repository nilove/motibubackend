<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/

	'connections' => array(
        // DONT UNCOMMENT THIS BLOCK, RATHER CREATE an .env.local.php and add the environment variables
        // Here goes the content of my .env.local.php
        /***
         * <?php

            return [
                'DB_HOST' => 'localhost',
                'DB_NAME' => 'motibu',
                'DB_USERNAME' => 'root',
                'DB_PASSWORD' => 'c0mm0n'
            ];
         */
		'mysql' => array(
			'driver'    => 'mysql',
            'host'      => getenv('DB_HOST'),
            'database'  => getenv('DB_NAME'),
            'username'  => getenv('DB_USERNAME'),
            'password'  => getenv('DB_PASSWORD'),
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
			// 'unix_socket' => '/tmp/mysql.sock'
		),


        'myssql' => array(
		 	'driver'    => 'mysql',
		 	'host'      => getenv('DB_HOST'),
		 	'database'  => getenv('DB_NAME'),
		 	'username'  => getenv('DB_USERNAME'),
		 	'password'  => getenv('DB_PASSWORD'),
		 	'charset'   => 'utf8',
		 	'collation' => 'utf8_unicode_ci',
		 	'prefix'    => '',
		 ),

		'pgsql' => array(
			'driver'   => 'pgsql',
			'host'     => 'localhost',
			'database' => 'homestead',
			'username' => 'homestead',
			'password' => 'secret',
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => 'public',
		),

	),

);
