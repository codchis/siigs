<?php if ( ! defined('BASEPATH')) exit('No se permite acceso directo al script');

/**
 * Menu Builder
 * 
 * @package    Libreria
 * @subpackage Clase
 * @author     Pascual
 * @created    2013-01-14
 */

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
     * Construye el menu basandose en los permisos del usuario logeado
     *
     * @access public
     * @param  boolean $todos Establece si se debe devolver todos los elementos del menu
     * @return string
     */
    public static function build($todos=false)
    {
        self::$CI->load->helper('phpquery');
        
        $strMenu = '<ul class="nav">';

        if(self::$CI->session->userdata(GROUP_ID)) {
            self::$CI->load->model(DIR_SIIGS.'/Controlador_model');
            self::$CI->load->model(DIR_SIIGS.'/Entorno_model');
            self::$CI->load->model(DIR_SIIGS.'/Menu_model');
            self::$CI->load->model(DIR_SIIGS.'/Bitacora_model');
            self::$CI->load->model(DIR_SIIGS.'/Usuario_model');

            if(!$todos)
                $strMenu .= '<li><a href="/index" id="0">Inicio</a></li>';
            
            self::crearMenu($strMenu, 'NULL', $todos);
            
            if(!$todos)
                $strMenu .= '<li><a href="/siigs/usuario/logout">Cerrar sesión</a></li>';
            
            $strMenu .= '</ul>';
        }
        
        if(self::$CI->session->userdata(USERNAME)) {
            $doc = phpQuery::newDocument($strMenu);

            if(!Usuario_model::checkCredentials('TES::reporte_sincronizacion::lote', ''))
                pq("a:contains('Lote Vacunación']")->parent()->remove();
            
            pq('ul:empty')->parent()->remove();
            pq('ul:empty')->parent()->remove();
            pq("a:contains('eTAB']")->parent()->remove();
            
            return $doc->html();
        } else {
            return $strMenu;
        }
    }

    /**
     * Función recursiva que recorre todo el árbol de elementos del menu
     *
     * @access public
     * @param  string $strMenu  Variable pasada por referencia donde se guardará la cadena de ul y li
     * @param  string $id_padre ID del padre de los elementos del arbol de menu
     * @return void
     */
    public static function crearMenu(&$strMenu, $id_padre='NULL', $todos=false)
    {
        $items = self::$CI->Menu_model->getByPadre($id_padre);
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

                if(!$todos) {
                    // Revisa permisos de acceso para el controlador especifico
                    if(!Usuario_model::checkCredentials($entorno->directorio.'::'.$controlador->clase.'::index', '')) {
                        continue; // Ignorar la secuencia normal y Seguir con la iteraccion del foreach
                    }
                }
            }
            
            $hijos = self::$CI->Menu_model->hasChild($item->id);
            
            if($todos)
                $strMenu .= '<li class="expanded" id="'.$item->id.'">'.$item->nombre.($hijos ? ' >>' : '');
            else
                $strMenu .= '<li class="expanded" id="'.$item->id.'"><a href="'.$ruta.'" '.$item->atributo.'>'.$item->nombre.($hijos ? ' >>' : '').'</a>';

            if ($hijos) {
                $strMenu .= '<ul>';
                self::crearMenu($strMenu, $item->id, $todos);
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