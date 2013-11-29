<?php if ( ! defined('BASEPATH')) exit('No se permite acceso directo al script');

class Menubuilder
{
    /**
     * Guarda la instancia del objeto global CodeIgniter
     * para utilizarlo en la función estática
     *
     * @access private
     * @var    instance
     */
    private static $CI;

    public function __construct()
    {
        self::$CI = &get_instance();
    }

    /**
     * contruye el menu
     *
     * @access public
     * @return string
     */
    public static function build()
    {
        $strMenu = '';

        if(self::$CI->session->userdata(GROUP_ID)) {
            self::$CI->load->model(DIR_SIIGS.'/Controlador_model');
            self::$CI->load->model(DIR_SIIGS.'/Entorno_model');
            self::$CI->load->model(DIR_SIIGS.'/Menu_model');
            self::$CI->load->model(DIR_SIIGS.'/Bitacora_model');
            self::$CI->load->model(DIR_SIIGS.'/Usuario_model');

            $strMenu = '<ul class="menu">';
            self::crearMenu($strMenu);
            $strMenu .= '</ul>';
        }
        //echo $strMenu;

        return $strMenu;
    }

    /**
     * Función recursiva que recorre todo el árbol de elementos del menu
     *
     * @access public
     * @param  string $strMenu  Variable pasada por referencia donde se guardará la cadena de ul y li
     * @param  string $id_padre ID del padre de los elementos del arbol de menu
     * @return void
     */
    public static function crearMenu(&$strMenu, $id_padre = 'NULL')
    {
        $items = self::$CI->Menu_model->getByPadre($id_padre);//$this->Menu_model->getByPadre($id_padre);
        $controlador = '';
        $entorno = '';

        foreach($items as $item) {
            $ruta = '#';

            if($item->ruta && $item->ruta!='#') {
                if(strpos($item->ruta, 'http') === FALSE)
                    $ruta = '/'.$item->ruta;
                else
                    $ruta = $item->ruta;
            }

            if($item->id_controlador) {
                $controlador = self::$CI->Controlador_model->getById($item->id_controlador);//$this->Controlador_model->getById($item->id_controlador);
                $entorno = self::$CI->Entorno_model->getById($controlador->id_entorno);//$this->Entorno_model->getById($controlador->id_entorno);

                $ruta = '/'.$entorno->directorio.'/'.$controlador->clase;

                // Revisa permisos de acceso para el controlador especifico
                if(!Usuario_model::checkCredentials($entorno->directorio.'::'.$controlador->clase.'::index', '')) {
                    continue; // Ignorar la secuencia normal y Seguir con la iteraccion del foreach
                }
            }

            $strMenu .= '<li class="menu-item"><a href="'.$ruta.'">'.$item->nombre.'</a>';

            if (self::$CI->Menu_model->hasChild($item->id)) {
                $strMenu .= '<ul class="menu-item">';
                self::crearMenu($strMenu, $item->id);
                $strMenu .= '</ul>';
            }
            $strMenu .= '</li>';
        }
    }
    
    /**
     * Función que valida si se visualiza o no la accion especificada (usada en views)
     *
     * @access public
     * @param  string $strMenu  Variable pasada por referencia donde se guardará la cadena de ul y li
     * @param  string $id_padre ID del padre de los elementos del arbol de menu
     * @return void
     */
    public static function isGranted($accion)
    {
    	if (self::$CI->session->userdata(PERMISSIONS)){
    		foreach(self::$CI->session->userdata(PERMISSIONS) as $k=>$v) {
    			if(array_search($accion, $v)) {
    				return true;
    			}
    		}
    	}
    	return false;
    }
}