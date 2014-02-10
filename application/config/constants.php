<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
define('DIR_SIIGS', 'siigs');
define('DIR_TES', 'tes');
define('CAT_POBLACION', 'cat_poblacion');
define('CAT_GEOREFERENCIA', 'cat_georeferencia');
define('CAT_AGEB', 'cat_ageb');
define('CATALOGOSCSV', '"cns_vacuna","cns_nacionalidad","cns_accion_nutricional","cns_afiliacion","cns_operadora_celular","cns_alergia","cns_tipo_sanguineo","cns_altura_x_edad","cns_peso_x_edad","cns_imc_x_edad","cns_tratamiento","cns_estado_visita","'.CAT_POBLACION.'","'.CAT_GEOREFERENCIA.'","'.CAT_AGEB.'"');
define('USERNAME', 'username');
define('USER_LOGGED', 'user_id');
define('GROUP_ID', 'group_id');
define('REDIRECT_TO', 'redirect_to');
define('PERMISSIONS', 'permissions');
define('VACUNA_APLICADA', 'x');
define('VACUNA_NOAPLICADA', '');

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/* End of file constants.php */
/* Location: ./application/config/constants.php */