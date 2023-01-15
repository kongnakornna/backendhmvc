<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Memcached settings
| -------------------------------------------------------------------------
| Your Memcached servers can be specified below.
|
|	See: https://codeigniter.com/user_guide/libraries/caching.html#memcached
|
*/
$config = array(
	'default' => array(
		'hostname' => 'TPPYMem1.ppm.in.th',
		'port'     => '11211',
		'weight'   => '1',
		'persistent'	=> TRUE
	),
		array(
			'host'			=> 'TPPYMem2.ppm.in.th',
			'port'			=> '11211',
			'weight'		=> '1',
			'persistent'	=> TRUE
	)
);
// --------------------------------------------------------------------------
// Servers
// --------------------------------------------------------------------------
$memcached['servers'] = array(
     'default'=>array('host'=>'TPPYMem.ppm.in.th',
     			'port'=>'11211',
     			'weight'=>'1',
     			'persistent'=>TRUE
			),array('host'=>'TPPYMem1.ppm.in.th',
     			'port'=>'11211',
     			'weight'=>'1',
     			'persistent'=>TRUE
			),
			array('host'=>'TPPYMem2.ppm.in.th',
				'port'=>'11211',
				'weight'=>'1',
				'persistent'=>TRUE
			),array(
				'host'=>'TPPYMem3.ppm.in.th',
				'port'=>'11211',
				'weight'=>'1',
				'persistent'=>TRUE
			)
               /*
               ,array(
				'host'=>'TPPYMem4.ppm.in.th',
				'port'=>'11211',
				'weight'=>'1',
				'persistent'=>TRUE
			),array(
				'host'=>'TPPYMem5.ppm.in.th',
				'port'=>'11211',
				'weight'=>'1',
				'persistent'=>TRUE
			)
               */
               
);
// --------------------------------------------------------------------------
// Configuration
// --------------------------------------------------------------------------
$memcached['config'] = array(
	'engine'=>'Memcached',// Set which caching engine you are using. Acceptable values: Memcached or Memcache
	'prefix'=>'',// Prefixes every key value (useful for multi environment setups)
	'compression'=> FALSE,// Default: FALSE or MEMCACHE_COMPRESSED Compression Method (Memcache only).

// Not necessary if you already are using 'compression'
	'auto_compress_tresh'=> FALSE,// Controls the minimum value length before attempting to compress automatically.
	'auto_compress_savings'=> 0.2,// Specifies the minimum amount of savings to actually store the value compressed. The supplied value must be between 0 and 1.

	'expiration'=> 3600,// Default content expiration value (in seconds)
	'delete_expiration'=>0// Default time between the delete command and the actual delete action occurs (in seconds)

);
$config['memcached'] = $memcached;
/* End of file memcached.php */
/* Location: ./system/application/config/memcached.php */