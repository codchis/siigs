<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['bitacora/index/pag/(:num)'] = 'bitacora/index/$1';
$route['bitacora/pag/(:num)'] = 'bitacora/index/$1';
$route['bitacora/(:num)'] = 'bitacora/view/$1';
$route['default_controller'] = "index";
$route['404_override'] = '';

/*Rutas para los entornos*/
$route['entorno/(:num)'] = 'entorno/view/$1';

/*Ruta para los controladores*/
$route['controlador/index/pag/(:num)'] = 'controlador/index/$1';
$route['controlador/pag/(:num)'] = 'controlador/index/$1';
$route['controlador/(:num)'] = 'controlador/view/$1';

/*Rutas para las acciones*/
$route['accion/(:num)'] = '/accion/view/$1';

/*Ruta para los permisos*/
$route[DIR_SIIGS.'/grupo/(:num)/permiso'] = DIR_SIIGS.'/permiso/index/$1';
$route[DIR_SIIGS.'/grupo/(:num)/update'] = DIR_SIIGS.'/permiso/update/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */