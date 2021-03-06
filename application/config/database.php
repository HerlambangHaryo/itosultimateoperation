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
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7.
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

// DEVELOPMENT

$db['default']['hostname'] = '10.88.48.93:1521/JICT2QA';
$db['default']['username'] = 'ITOS_OP';
$db['default']['password'] = 'itos_oP';
$db['default']['database'] = 'ITOS_OP';
$db['default']['dbdriver'] = 'oci8';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;
/*
$db['billing']['hostname'] = '10.10.33.150:1521/jambitosdb';
$db['billing']['username'] = 'ITOS_BILLING';
$db['billing']['password'] = 'itos_BILLING';
$db['billing']['database'] = 'ITOS_BILLING';
$db['billing']['dbdriver'] = 'oci8';
$db['billing']['dbprefix'] = '';
$db['billing']['pconnect'] = TRUE;
$db['billing']['db_debug'] = TRUE;
$db['billing']['cache_on'] = FALSE;
$db['billing']['cachedir'] = '';
$db['billing']['char_set'] = 'utf8';
$db['billing']['dbcollat'] = 'utf8_general_ci';
$db['billing']['swap_pre'] = '';
$db['billing']['autoinit'] = TRUE;
$db['billing']['stricton'] = FALSE;
*/
$db['repo']['hostname'] = '10.88.48.93:1521/JICT2QA';
//$db['repo']['hostname'] = '10.10.33.150:1521/bantentosdb';
$db['repo']['username'] = 'ITOS_REPO';
$db['repo']['password'] = 'itos_repO';
$db['repo']['database'] = 'ITOS_REPO';
$db['repo']['dbdriver'] = 'oci8';
$db['repo']['dbprefix'] = '';
$db['repo']['pconnect'] = TRUE;
$db['repo']['db_debug'] = TRUE;
$db['repo']['cache_on'] = FALSE;
$db['repo']['cachedir'] = '';
$db['repo']['char_set'] = 'utf8';
$db['repo']['dbcollat'] = 'utf8_general_ci';
$db['repo']['swap_pre'] = '';
$db['repo']['autoinit'] = TRUE;
$db['repo']['stricton'] = FALSE;

// END DEVELOPMENT

// PRODUCTION TO3

// $db['default']['hostname'] = '10.10.33.147:1521/meraktosdb';
// $db['default']['username'] = 'ITOS_OP';
// $db['default']['password'] = 'itos_OP';
// $db['default']['database'] = 'ITOS_OP';
// $db['default']['dbdriver'] = 'oci8';
// $db['default']['dbprefix'] = '';
// $db['default']['pconnect'] = TRUE;
// $db['default']['db_debug'] = TRUE;
// $db['default']['cache_on'] = FALSE;
// $db['default']['cachedir'] = '';
// $db['default']['char_set'] = 'utf8';
// $db['default']['dbcollat'] = 'utf8_general_ci';
// $db['default']['swap_pre'] = '';
// $db['default']['autoinit'] = TRUE;
// $db['default']['stricton'] = FALSE;

// $db['billing']['hostname'] = '10.10.33.147:1521/meraktosdb';
// $db['billing']['username'] = 'ITOS_BILLING';
// $db['billing']['password'] = 'itos_BILLING';
// $db['billing']['database'] = 'ITOS_BILLING';
// $db['billing']['dbdriver'] = 'oci8';
// $db['billing']['dbprefix'] = '';
// $db['billing']['pconnect'] = TRUE;
// $db['billing']['db_debug'] = TRUE;
// $db['billing']['cache_on'] = FALSE;
// $db['billing']['cachedir'] = '';
// $db['billing']['char_set'] = 'utf8';
// $db['billing']['dbcollat'] = 'utf8_general_ci';
// $db['billing']['swap_pre'] = '';
// $db['billing']['autoinit'] = TRUE;
// $db['billing']['stricton'] = FALSE;

// $db['repo']['hostname'] = '10.10.33.147:1521/meraktosdb';
// $db['repo']['username'] = 'ITOS_REPO';
// $db['repo']['password'] = 'itos_REPO';
// $db['repo']['database'] = 'ITOS_REPO';
// $db['repo']['dbdriver'] = 'oci8';
// $db['repo']['dbprefix'] = '';
// $db['repo']['pconnect'] = TRUE;
// $db['repo']['db_debug'] = TRUE;
// $db['repo']['cache_on'] = FALSE;
// $db['repo']['cachedir'] = '';
// $db['repo']['char_set'] = 'utf8';
// $db['repo']['dbcollat'] = 'utf8_general_ci';
// $db['repo']['swap_pre'] = '';
// $db['repo']['autoinit'] = TRUE;
// $db['repo']['stricton'] = FALSE;

// END PRODUCTION TO3

// $db['default']['hostname'] = '10.10.12.204:1521/to1';
// $db['default']['username'] = 'ITOS_OP';
// $db['default']['password'] = 'itos_OP';
// $db['default']['database'] = 'ITOS_OP';
// $db['default']['dbdriver'] = 'oci8';
// $db['default']['dbprefix'] = '';
// $db['default']['pconnect'] = TRUE;
// $db['default']['db_debug'] = TRUE;
// $db['default']['cache_on'] = FALSE;
// $db['default']['cachedir'] = '';
// $db['default']['char_set'] = 'utf8';
// $db['default']['dbcollat'] = 'utf8_general_ci';
// $db['default']['swap_pre'] = '';
// $db['default']['autoinit'] = TRUE;
// $db['default']['stricton'] = FALSE;

// $db['billing']['hostname'] = '10.10.12.204:1521/to1';
// $db['billing']['username'] = 'ITOS_BILLING';
// $db['billing']['password'] = 'itos_BILLING';
// $db['billing']['database'] = 'ITOS_BILLING';
// $db['billing']['dbdriver'] = 'oci8';
// $db['billing']['dbprefix'] = '';
// $db['billing']['pconnect'] = TRUE;
// $db['billing']['db_debug'] = TRUE;
// $db['billing']['cache_on'] = FALSE;
// $db['billing']['cachedir'] = '';
// $db['billing']['char_set'] = 'utf8';
// $db['billing']['dbcollat'] = 'utf8_general_ci';
// $db['billing']['swap_pre'] = '';
// $db['billing']['autoinit'] = TRUE;
// $db['billing']['stricton'] = FALSE;

// $db['repo']['hostname'] = '10.10.12.204:1521/to1';
// $db['repo']['username'] = 'ITOS_REPO';
// $db['repo']['password'] = 'itos_REPO';
// $db['repo']['database'] = 'ITOS_REPO';
// $db['repo']['dbdriver'] = 'oci8';
// $db['repo']['dbprefix'] = '';
// $db['repo']['pconnect'] = TRUE;
// $db['repo']['db_debug'] = TRUE;
// $db['repo']['cache_on'] = FALSE;
// $db['repo']['cachedir'] = '';
// $db['repo']['char_set'] = 'utf8';
// $db['repo']['dbcollat'] = 'utf8_general_ci';
// $db['repo']['swap_pre'] = '';
// $db['repo']['autoinit'] = TRUE;
// $db['repo']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */