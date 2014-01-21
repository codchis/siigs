<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Active template
|--------------------------------------------------------------------------
|
| The $template['active_template'] setting lets you choose which template 
| group to make active.  By default there is only one group (the 
| "default" group).
|
*/
$template['active_template'] = 'default';

/*
|--------------------------------------------------------------------------
| Explaination of template group variables
|--------------------------------------------------------------------------
|
| ['template'] The filename of your master template file in the Views folder.
|   Typically this file will contain a full XHTML skeleton that outputs your
|   full template or region per region. Include the file extension if other
|   than ".php"
| ['regions'] Places within the template where your content may land. 
|   You may also include default markup, wrappers and attributes here 
|   (though not recommended). Region keys must be translatable into variables 
|   (no spaces or dashes, etc)
| ['parser'] The parser class/library to use for the parse_view() method
|   NOTE: See http://codeigniter.com/forums/viewthread/60050/P0/ for a good
|   Smarty Parser that works perfectly with Template
| ['parse_template'] FALSE (default) to treat master template as a View. TRUE
|   to user parser (see above) on the master template
|
| Region information can be extended by setting the following variables:
| ['content'] Must be an array! Use to set default region content
| ['name'] A string to identify the region beyond what it is defined by its key.
| ['wrapper'] An HTML element to wrap the region contents in. (We 
|   recommend doing this in your template file.)
| ['attributes'] Multidimensional array defining HTML attributes of the 
|   wrapper. (We recommend doing this in your template file.)
|
| Example:
| $template['default']['regions'] = array(
|    'header' => array(
|       'content' => array('<h1>Welcome</h1>','<p>Hello World</p>'),
|       'name' => 'Page Header',
|       'wrapper' => '<div>',
|       'attributes' => array('id' => 'header', 'class' => 'clearfix')
|    )
| );
|
*/

/*
|--------------------------------------------------------------------------
| Default Template Configuration (adjust this or create your own)
|--------------------------------------------------------------------------
*/

$template['default']['template'] = 'template.php';
$template['default']['regions'] = array(
   'header' => array('content' => array(file_get_contents('application/views/templates/header.php'))),
   'content',
   'ajustaAncho' => array('content' => array(0)),
   'js',
   'sala_prensa' => array('content' => array('<a href="http://www.icosochiapas.gob.mx/">Sala de Prensa:</a>
                <div>
                    <iframe src="http://www.chiapas.gob.mx/newstickernosune.php" width="600px" height="17"
                            marginwidth="0" marginheight="1" frameborder="0" scrolling="no" margintop="10px"> </iframe>
                </div>')),
   'footer' => array('content' => array(file_get_contents('application/views/templates/footer.php'))),
   'seccion_ayuda' => array('content' => array('<div style="float: right; display: inline; cursor: help;">'
       . '<a id="ayuda" href="/ayuda"><img src="/resources/images/icons28x28-09.png"></a></div>')),
   'menu'=> array('content' => array( Menubuilder::build() ) )
);
$template['default']['parser'] = 'parser';
$template['default']['parser_method'] = 'parse';
$template['default']['parse_template'] = FALSE;

/* End of file template.php */
/* Location: ./system/application/config/template.php */

//$template['default']['regions']['header'] = array('content' => array('<h1>CI Rocks!</h1>'));
//$template['default']['regions']['footer'] = array('content' => array('<p id="copyright">© Our Company Inc.</p>'));