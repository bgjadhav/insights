<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| PDO Fetch Style
	|--------------------------------------------------------------------------
	|
	| By default, database results will be returned as instances of the PHP
	| stdClass object; however, you may desire to retrieve records in an
	| array format for simplicity. Here you can tweak the fetch style.
	|
	*/

	'fetch' => PDO::FETCH_CLASS,

	/*
	|--------------------------------------------------------------------------
	| Default Database Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify which of the database connections below you wish
	| to use as your default connection for all database work. Of course
	| you may use many connections at once using the Database library.
	|
	*/

	'default' => 'mysql',

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

		'sqlite' => array(
			'driver'   => 'sqlite',
			'database' => __DIR__.'/../database/production.sqlite',
			'prefix'   => '',
		),

		'mysql' => array(
			'driver'    => 'mysql',
			'host'      => 'open-internal.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'floorplan',
			'username'  => 'floorplan',
			'password'  => 'sergiu_floorplan',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'update_process' => array(
			'driver'    => 'mysql',
			'host'      => 'open-analytics.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'open_analytics_update_process',
			'username'  => 'esmith',
			'password'  => 'S7uX4fmTwpepGTAD',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'dashboard' => array(
			'driver'    => 'mysql',
			'host'      => 'open-internal.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'dashboard',
			'username'  => 'dashboard',
			'password'  => 'GZc4yCugLsPF',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'pipeline' => array(
			'driver'    => 'mysql',
			'host'      => 'open-internal.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'PIPELINE',
			'username'  => 'dashboard',
			'password'  => 'GZc4yCugLsPF',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'jira_prod' => array(
			'driver'    => 'mysql',
			'host'      => 'open-internal.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'JIRA_PRODUCT',
			'username'  => 'dashboard',
			'password'  => 'GZc4yCugLsPF',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'jira_comm' => array(
			'driver'    => 'mysql',
			'host'      => 'open-internal.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'JIRA_COMM',
			'username'  => 'dashboard',
			'password'  => 'GZc4yCugLsPF',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'jira_intel' => array(
			'driver'    => 'mysql',
			'host'      => 'open-internal.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'JIRA_INTEL',
			'username'  => 'dashboard',
			'password'  => 'GZc4yCugLsPF',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'publisher_tool' => array(
			'driver'    => 'mysql',
			'host'      => 'open-internal.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'PUBLISHER_TOOL',
			'username'  => 'dashboard',
			'password'  => 'GZc4yCugLsPF',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'open' => array(
			'driver'    => 'mysql',
			'host'      => 'warroom.cctexsmpexr2.eu-west-1.rds.amazonaws.com',
			'database'  => 'OPEN_WORDPRESS',
			'username'  => 'ereeve',
			'password'  => 'DCvWVvushn4JMSz3',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'jira_db' => array(
			'driver'    => 'mysql',
			'host'      => 'open-internal.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'JIRA',
			'username'  => 'jira_cli',
			'password'  => 'HjqjH56jjeBt5dVY',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'analytics' => array(
			'driver'    => 'mysql',
			'host'      => 'open-analytics.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'partner_dashboard',
			'username'  => 'dashboard_read',
			'password'  => 'K2utxHpfbmFLTJyC',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'pmap_rev' => array(
			'driver'    => 'mysql',
			'host'      => 'open-analytics.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'jira_pmap_rev',
			'username'  => 'esmith',
			'password'  => 'S7uX4fmTwpepGTAD',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'match_rates' => array(
			'driver'    => 'mysql',
			'host'      => 'open-analytics.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'match_rates',
			'username'  => 'dashboard_read',
			'password'  => 'K2utxHpfbmFLTJyC',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'domain_verticals' => array(
			'driver'    => 'mysql',
			'host'      => 'open-analytics.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'domain_verticals',
			'username'  => 'dashboard_read',
			'password'  => 'K2utxHpfbmFLTJyC',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'audience_segments' => array(
			'driver'    => 'mysql',
			'host'      => 'open-analytics.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'audience_segments',
			'username'  => 'dashboard_read',
			'password'  => 'K2utxHpfbmFLTJyC',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'usage_write' => array(
			'driver'    => 'mysql',
			'host'      => 'open-analytics.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'partner_dashboard',
			'username'  => 'usage_write',
			'password'  => 'ilovedata',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'warroom' => array(
			'driver'    => 'mysql',
			'host'      => 'open-analytics.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'global_warroom',
			'username'  => 'dashboard_read',
			'password'  => 'K2utxHpfbmFLTJyC',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'warroom_write' => array(
			'driver'    => 'mysql',
			'host'      => 'open-analytics.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'global_warroom',
			'username'  => 'dashboard',
			'password'  => '8PL6cDzZU8wyR2Ac',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'users_v3' => array(
			'driver'    => 'mysql',
			'host'      => 'open-website-v3.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'OPEN_WEBSITE',
			'username'  => 'open',
			'password'  => 'w3l0v30p3n',
			'charset'   => 'utf8',
			'collation' => 'utf8_general_ci',
			'prefix'    => '',
		),

		'pgsql' => array(
			'driver'   => 'pgsql',
			'host'     => 'localhost',
			'database' => 'forge',
			'username' => 'forge',
			'password' => '',
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => 'public',
		),

		'sqlsrv' => array(
			'driver'   => 'sqlsrv',
			'host'     => 'localhost',
			'database' => 'database',
			'username' => 'root',
			'password' => '',
			'prefix'   => '',
		),

		'sergiu_partner_dashboard' => array(
			'driver'    => 'mysql',
			'host'      => 'open-analytics.cc2cezg5kv8d.us-east-1.rds.amazonaws.com',
			'database'  => 'partner_dashboard',
			'username'  => 'sergiu',
			'password'  => '8WxpTwefNdFaGzn3',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

	),

	/*
	|--------------------------------------------------------------------------
	| Migration Repository Table
	|--------------------------------------------------------------------------
	|
	| This table keeps track of all the migrations that have already run for
	| your application. Using this information, we can determine which of
	| the migrations on disk haven't actually been run in the database.
	|
	*/

	'migrations' => 'migrations',

	/*
	|--------------------------------------------------------------------------
	| Redis Databases
	|--------------------------------------------------------------------------
	|
	| Redis is an open source, fast, and advanced key-value store that also
	| provides a richer set of commands than a typical key-value systems
	| such as APC or Memcached. Laravel makes it easy to dig right in.
	|
	*/

	'redis' => array(

		'cluster' => false,

		'default' => array(
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 0,
		),

	),

);
