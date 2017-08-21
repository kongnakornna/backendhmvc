<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|   ['hostname'] The hostname of your database server.
|   ['username'] The username used to connect to the database
|   ['password'] The password used to connect to the database
|   ['database'] The name of the database you want to connect to
|   ['dbdriver'] The database type. ie: mysql.  Currently supported:
                 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|   ['dbprefix'] You can add an optional prefix, which will be added
|                to the table name when using the  Active Record class
|   ['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|   ['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|   ['cache_on'] TRUE/FALSE - Enables/disables query caching
|   ['cachedir'] The path to the folder where cache files should be stored
|   ['char_set'] The character set used in communicating with the database
|   ['dbcollat'] The character collation used in communicating with the database
|                NOTE: For MySQL and MySQLi databases, this setting is only used
|                as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|                (and in table creation queries made with DB Forge).
|                There is an incompatibility in PHP with mysql_real_escape_string() which
|                can make your site vulnerable to SQL injection if you are using a
|                multi-byte character set and are running versions lower than these.
|                Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|   ['swap_pre'] A default table prefix that should be swapped with the dbprefix
|   ['autoinit'] Whether or not to automatically initialize the database.
|   ['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|                           - good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/
$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'TPPYDB.ppm.in.th';
$db['default']['username'] = 'trueplookpanya';
$db['default']['password'] = 'Gc1D6bD5A231LDM';
$db['default']['database'] = 'trueplookpanya';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['port'] 		= 3306;
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


$db['select']['hostname'] = 'TPPYDB.ppm.in.th';
$db['select']['username'] = 'trueplookpanya';
$db['select']['password'] = 'Gc1D6bD5A231LDM';
$db['select']['database'] = 'trueplookpanya';
$db['select']['dbdriver'] = 'mysqli';
$db['select']['dbprefix'] = '';
$db['select']['port'] 		= 3306;
$db['select']['pconnect'] = TRUE;
$db['select']['db_debug'] = FALSE;
$db['select']['cache_on'] = FALSE;
$db['select']['cachedir'] = '';
$db['select']['char_set'] = 'utf8';
$db['select']['dbcollat'] = 'utf8_general_ci';
$db['select']['swap_pre'] = '';
$db['select']['autoinit'] = TRUE;
$db['select']['stricton'] = FALSE;

$db['edit']['hostname'] = 'TPPYDB.ppm.in.th';
$db['edit']['username'] = 'trueplookpanya';
$db['edit']['password'] = 'Gc1D6bD5A231LDM';
$db['edit']['database'] = 'trueplookpanya';
$db['edit']['dbdriver'] = 'mysqli';
$db['edit']['dbprefix'] = '';
$db['edit']['port'] 		= 3306;
$db['edit']['pconnect'] = TRUE;
$db['edit']['db_debug'] = FALSE;
$db['edit']['cache_on'] = FALSE;
$db['edit']['cachedir'] = '';
$db['edit']['char_set'] = 'utf8';
$db['edit']['dbcollat'] = 'utf8_general_ci';
$db['edit']['swap_pre'] = '';
$db['edit']['autoinit'] = TRUE;
$db['edit']['stricton'] = FALSE;

$db['edit_competition']['hostname'] = 'TPPYDB.ppm.in.th';
$db['edit_competition']['username'] = 'trueplookpanya';
$db['edit_competition']['password'] = 'Gc1D6bD5A231LDM';
$db['edit_competition']['database'] = "truecompetition";
$db['edit_competition']['dbdriver'] = 'mysqli';
$db['edit_competition']['dbprefix'] = '';
$db['edit_competition']['port'] = 3306;
$db['edit_competition']['pconnect'] = TRUE;
$db['edit_competition']['db_debug'] = FALSE;
$db['edit_competition']['cache_on'] = FALSE;
$db['edit_competition']['cachedir'] = '';
$db['edit_competition']['char_set'] = 'utf8';
$db['edit_competition']['dbcollat'] = 'utf8_general_ci';
$db['edit_competition']['swap_pre'] = '';
$db['edit_competition']['autoinit'] = TRUE;
$db['edit_competition']['stricton'] = FALSE;

$db['sqlidb']['hostname'] = '';
$db['sqlidb']['username'] = '';
$db['sqlidb']['password'] = '';
$db['sqlidb']['database'] = 'sqlite:data/product/trueplookpanya/www/static/trueplookpanya/media/hash_csa/api2/csa.sqlite';
$db['sqlidb']['dbdriver'] = 'pdo';

/* End of file database.php */
/* Location: ./application/config/database.php */
