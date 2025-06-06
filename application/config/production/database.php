<?php defined('BASEPATH') || exit('No direct script access allowed');
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
| EXPLANATION OF VARIABLES (CI2)
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
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES (CI3)
| -------------------------------------------------------------------
|
|   ['dsn']      The full DSN string describe a connection to the database.
|   ['hostname'] The hostname of your database server.
|   ['username'] The username used to connect to the database
|   ['password'] The password used to connect to the database
|   ['database'] The name of the database you want to connect to
|   ['dbdriver'] The database driver. e.g.: mysqli.
|           Currently supported:
|                cubrid, ibase, mssql, mysql, mysqli, oci8,
|                odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|   ['dbprefix'] You can add an optional prefix, which will be added
|                to the table name when using the  Query Builder class
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
|   ['encrypt']  Whether or not to use an encrypted connection.
|   ['compress'] Whether or not to use client compression (MySQL only)
|   ['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|                           - good for ensuring strict SQL while developing
|   ['failover'] array - A array with 0 or more data for connections if the main should fail.
|   ['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
|               NOTE: Disabling this will also effectively disable both
|               $this->db->last_query() and profiling of DB queries.
|               When you run a query, with this setting set to TRUE (default),
|               CodeIgniter will store the SQL statement for debugging purposes.
|               However, this may cause high memory usage, especially if you run
|               a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/

$active_group = 'default';

if (defined('CI_VERSION') && substr(CI_VERSION, 0, 1) != '2') {
    // CodeIgniter 3 configuration
    // test update
    //
    $query_builder = true;
    $db['default'] = array(
        'dsn'          => '',
        'hostname'     => getenv('DB_HOST'),
        'username'     => getenv('DB_USER'),
        'password'     => getenv('DB_PASS'),
        'database'     => getenv('DB_NAME'),
        'dbdriver'     => 'postgre',
        'schema'       => 'hris',
        'port'         => '5432',
        'dbprefix'     => '',
        'pconnect'     => false, // not supported with the database session driver
        'db_debug'     => true,
        'cache_on'     => false,
        'cachedir'     => '',
        'char_set'     => 'utf8',
        'dbcollat'     => 'utf8_general_ci',
        'swap_pre'     => '',
        'encrypt'      => false,
        'compress'     => false,
        'stricton'     => true,
        'failover'     => array(),
        'save_queries' => true,
    );

    $db['kehadiran'] = array(
        'hostname'     => '******',
        'username'     => '******',
        'password'     => '******',
        'database'     => 'diknas',
        'dbdriver'     => 'postgre',
        'schema'       => 'public',
        'port'       => '5432',
        'dbprefix'     => '',
        'pconnect'     => false, // not supported with the database session driver
        'db_debug'     => true,
        'cache_on'     => false,
        'cachedir'     => '',
        'char_set'     => 'utf8',
        'dbcollat'     => 'utf8_general_ci',
        'swap_pre'     => '',
        'encrypt'      => false,
        'compress'     => false,
        'stricton'     => true,
        'failover'     => array(),
        'save_queries' => true,
    );
    // db mutasi
    $db['db_mysql_local'] = array(
        'dsn'          => '',
        'hostname'     => '****',
        'username'     => '****',
        'password'     => '****',
        'database'     => 'db_biro_kepegawaian_2016',
        'dbdriver'     => 'mysqli',
        'dbprefix'     => '',
        'pconnect'     => false, // not supported with the database session driver
        'db_debug'     => true,
        'cache_on'     => false,
        'cachedir'     => '',
        'char_set'     => 'utf8',
        'dbcollat'     => 'utf8_general_ci',
        'swap_pre'     => '',
        'encrypt'      => false,
        'compress'     => false,
        'stricton'     => true,
        'failover'     => array(),
        'save_queries' => true,
    );

} else {
    // CodeIgniter 2 configuration
    $active_record = true;

    $db['default']['hostname'] = 'localhost';
    $db['default']['username'] = '';
    $db['default']['password'] = '';
    $db['default']['database'] = '';
    $db['default']['port']     = '';
    $db['default']['dbdriver'] = 'bfmysqli';
    $db['default']['dbprefix'] = 'bf_';
    $db['default']['pconnect'] = true;
    $db['default']['db_debug'] = true;
    $db['default']['cache_on'] = false;
    $db['default']['cachedir'] = '';
    $db['default']['char_set'] = 'utf8';
    $db['default']['dbcollat'] = 'utf8_general_ci';
    $db['default']['swap_pre'] = '';
    $db['default']['autoinit'] = true;
    $db['default']['stricton'] = true;
}
