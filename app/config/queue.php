<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Queue Driver
	|--------------------------------------------------------------------------
	|
	| The Laravel queue API supports a variety of back-ends via an unified
	| API, giving you convenient access to each back-end using the same
	| syntax for each one. Here you may set the default queue driver.
	|
	| Supported: "sync", "beanstalkd", "sqs", "iron", "redis"
	|
	*/

	'default' => 'beanstalkd',

	/*
	|--------------------------------------------------------------------------
	| Queue Connections
	|--------------------------------------------------------------------------
	|
	| Here you may configure the connection information for each server that
	| is used by your application. A default configuration has been added
	| for each back-end shipped with Laravel. You are free to add more.
	|
	*/

	'connections' => array(

		'sync' => array(
			'driver' => 'sync',
		),

		'high' => array(
			'driver' => 'beanstalkd',
			'host'   => 'localhost',
			'queue'  => 'high',
			'ttr'    => 600
		),

		'low' => array(
			'driver' => 'beanstalkd',
			'host'   => 'localhost',
			'queue'  => 'low',
			'ttr'    => 600
		),

		'beanstalkd' => array(
			'driver' => 'beanstalkd',
			'host'   => 'localhost',
			'queue'  => 'default',
			'ttr'    => 600,
		),

		'sqs' => array(
			'driver' => 'sqs',
			'key'    => 'your-public-key',
			'secret' => 'your-secret-key',
			'queue'  => 'your-queue-url',
			'region' => 'us-east-1',
		),

		'iron' => array(
			'driver'  => 'iron',
			'host'    => 'mq-aws-us-east-1.iron.io',
			'token'   => 'your-token',
			'project' => 'your-project-id',
			'queue'   => 'your-queue-name',
			'encrypt' => true,
		),

		'redis' => array(
			'driver' => 'redis',
			'queue'  => 'default',
		),

	),

	/*
	|--------------------------------------------------------------------------
	| Failed Queue Jobs
	|--------------------------------------------------------------------------
	|
	| These options configure the behavior of failed queue job logging so you
	| can control which database and table are used to store the jobs that
	| have failed. You may change them to any database / table you wish.
	|
	*/

	'failed' => array(

		'database' => 'dashboard',
		'table' => 'failed_jobs'

	),

);
