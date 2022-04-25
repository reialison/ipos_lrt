<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$active_group = 'default';
$active_record = TRUE;
$db['default']['hostname'] = '192.168.1.12';
$db['default']['username'] = 'CASHIER';
$db['default']['password'] = '';
// $db['default']['database'] = 'ipos';

$db['default']['database'] = 'viamare_evia';
// $db['default']['database'] = 'dine_hap_vigan';
$db['default']['dbdriver'] = 'mysql';
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
##################################################
$db['main']['hostname'] = '192.168.1.12';
$db['main']['username'] = 'CASHIER';
$db['main']['password'] = '';
$db['main']['database'] = 'viamare_evia_main';
$db['main']['dbdriver'] = 'mysql';
$db['main']['dbprefix'] = '';
$db['main']['pconnect'] = FALSE;
$db['main']['db_debug'] = TRUE;
$db['main']['cache_on'] = FALSE;
$db['main']['cachedir'] = '';
$db['main']['char_set'] = 'utf8';
$db['main']['dbcollat'] = 'utf8_general_ci';
$db['main']['swap_pre'] = '';
$db['main']['autoinit'] = TRUE;
$db['main']['stricton'] = FALSE;

// $db['bir']['hostname'] = '192.168.254.108';
// $db['bir']['username'] = 'pointone';
// $db['bir']['password'] = '';
// $db['bir']['database'] = 'accounting_np';// $db['default']['database'] = 'inland';
// $db['bir']['dbdriver'] = 'mysql';
// $db['bir']['dbprefix'] = '0_';
// $db['bir']['pconnect'] = TRUE;
// $db['bir']['db_debug'] = TRUE;
// $db['bir']['cache_on'] = FALSE;
// $db['bir']['cachedir'] = '';
// $db['bir']['char_set'] = 'utf8';
// $db['bir']['dbcollat'] = 'utf8_general_ci';
// $db['bir']['swap_pre'] = '';
// $db['bir']['autoinit'] = TRUE;
// $db['bir']['stricton'] = FALSE;

$db['bir']['hostname'] = '192.168.254.108';
$db['bir']['username'] = 'pointone';
$db['bir']['password'] = '';
$db['bir']['database'] = 'accounting_np';// $db['default']['database'] = 'inland';
$db['bir']['dbdriver'] = 'mysql';
$db['bir']['dbprefix'] = '0_';
$db['bir']['pconnect'] = FALSE;
$db['bir']['db_debug'] = FALSE;
$db['bir']['cache_on'] = FALSE;
$db['bir']['cachedir'] = '';
$db['bir']['char_set'] = 'utf8';
$db['bir']['dbcollat'] = 'utf8_general_ci';
$db['bir']['swap_pre'] = '';
$db['bir']['autoinit'] = TRUE;
$db['bir']['stricton'] = FALSE;

// $db['main_sync']['hostname'] = 'localhost';
// $db['main_sync']['username'] = 'root';
// $db['main_sync']['password'] = '';
// $db['main_sync']['database'] = 'ipos_test_barcino_main_sync';
// $db['main_sync']['dbdriver'] = 'mysql';
// $db['main_sync']['dbprefix'] = '';
// $db['main_sync']['pconnect'] = FALSE;
// $db['main_sync']['db_debug'] = TRUE;
// $db['main_sync']['cache_on'] = FALSE;
// $db['main_sync']['cachedir'] = '';
// $db['main_sync']['char_set'] = 'utf8';
// $db['main_sync']['dbcollat'] = 'utf8_general_ci';
// $db['main_sync']['swap_pre'] = '';
// $db['main_sync']['autoinit'] = FALSE;
// $db['main_sync']['stricton'] = FALSE;

##################################################
// $db['master']['hostname'] = 'localhost';
// $db['master']['username'] = 'root';
// $db['master']['password'] = '';
// $db['master']['database'] = 'pinkberry_hq';

// $db['master']['dbdriver'] = 'mysql';
// $db['master']['dbprefix'] = '';
// $db['master']['pconnect'] = FALSE;
// $db['master']['db_debug'] = TRUE;
// $db['master']['cache_on'] = FALSE;
// $db['master']['cachedir'] = '';
// $db['master']['char_set'] = 'utf8';
// $db['master']['dbcollat'] = 'utf8_general_ci';
// $db['master']['swap_pre'] = '';
// $db['master']['autoinit'] = FALSE;
// $db['master']['stricton'] = FALSE;


$db['master']['hostname'] = 'localhost';
$db['master']['username'] = 'root';
$db['master']['password'] = '';
$db['master']['database'] = 'point1ph_locavore_hq';
$db['master']['dbdriver'] = 'mysql';
$db['master']['dbprefix'] = '';
$db['master']['pconnect'] = FALSE;
$db['master']['db_debug'] = TRUE;
$db['master']['cache_on'] = FALSE;
$db['master']['cachedir'] = '';
$db['master']['char_set'] = 'utf8';
$db['master']['dbcollat'] = 'utf8_general_ci';
$db['master']['swap_pre'] = '';
$db['master']['autoinit'] = FALSE;
$db['master']['stricton'] = FALSE;


// $db['master']['hostname'] = '209.159.151.148';
// $db['master']['username'] = 'pointone_root';
// $db['master']['password'] = 'p0!nt0n3';
// $db['master']['database'] = 'pointone_dine_master';
// $db['master']['dbdriver'] = 'mysql';
// $db['master']['dbprefix'] = '';
// $db['master']['pconnect'] = FALSE;
// $db['master']['db_debug'] = TRUE;
// $db['master']['cache_on'] = FALSE;
// $db['master']['cachedir'] = '';
// $db['master']['char_set'] = 'utf8';
// $db['master']['dbcollat'] = 'utf8_general_ci';
// $db['master']['swap_pre'] = '';
// $db['master']['autoinit'] = FALSE;
// $db['master']['stricton'] = FALSE;

// $db['master']['hostname'] = '69.10.37.166';
// $db['master']['username'] = 'pointone_root';
// $db['master']['password'] = 'p0!nt0n3';
// $db['master']['database'] = 'pointone_dine_master';
// $db['master']['dbdriver'] = 'mysql';
// $db['master']['dbprefix'] = '';
// $db['master']['pconnect'] = FALSE;
// $db['master']['db_debug'] = TRUE;
// $db['master']['cache_on'] = FALSE;
// $db['master']['cachedir'] = '';
// $db['master']['char_set'] = 'utf8';
// $db['master']['dbcollat'] = 'utf8_general_ci';
// $db['master']['swap_pre'] = '';
// $db['master']['autoinit'] = FALSE;
// $db['master']['stricton'] = FALSE;

// $db['custom']['hostname'] = 'localhost';
// $db['custom']['username'] = 'root';
// $db['custom']['password'] = '';
// $db['custom']['database'] = 'armscor';
// $db['custom']['dbdriver'] = 'mysql';
// $db['custom']['dbprefix'] = '';
// $db['custom']['pconnect'] = FALSE;
// $db['custom']['db_debug'] = TRUE;
// $db['custom']['cache_on'] = FALSE;
// $db['custom']['cachedir'] = '';
// $db['custom']['char_set'] = 'utf8';
// $db['custom']['dbcollat'] = 'utf8_general_ci';
// $db['custom']['swap_pre'] = '';
// $db['custom']['autoinit'] = FALSE;
// $db['custom']['stricton'] = FALSE;




/* End of file database.php */
/* Location: ./application/config/database.php */