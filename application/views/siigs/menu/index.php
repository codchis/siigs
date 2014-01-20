<script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/> 

<link   href="/resources/src/skin/ui.dynatree.css" rel="stylesheet" type="text/css" id="skinSheet">
<script src="/resources/src/jquery.dynatree.js" type="text/javascript"></script>

<script type="text/javascript">
DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';

$(document).ready(function(){
    $("#menuTree").dynatree({
        onClick: function(node, event) {
            $('#detalles').attr('href', $('#detalles').data('ruta')+node.data.key);
            $('#modificar').attr('href', $('#modificar').data('ruta')+node.data.key);
            $('#eliminar').attr('href', $('#eliminar').data('ruta')+node.data.key);
            $('#crear').attr('href', $('#crear').data('ruta')+node.data.key);
        },
        debugLevel: 0
    });
    
    $("a#detalles")
        .click(function(event){
            //rutaView = $(this).data('ruta');
            seleccionado = $("#menuTree").dynatree("getActiveNode");
            
            if(seleccionado == null) {
                alert('Debe seleccionar un elemento del menu');
                event.preventDefault();
                event.stopImmediatePropagation();
                event.stopPropagation();
                return false;
            }
        })
        .fancybox({
            'width'             : '50%',
            'height'            : '60%',				
            'transitionIn'	: 'elastic',
            'transitionOut'	: 'elastic',
            'type'			: 'iframe',	
        });
    
    $("#modificar").click(function(event){
        seleccionado = $("#menuTree").dynatree("getActiveNode");

        if(seleccionado == null) {
            alert('Debe seleccionar un elemento del menu');
            event.preventDefault();
            event.stopImmediatePropagation();
            event.stopPropagation();
            return false;
        }
    });
    
    $("#eliminar").click(function(event){
        seleccionado = $("#menuTree").dynatree("getActiveNode");

        if(seleccionado == null) {
            alert('Debe seleccionar un elemento del menu');
            event.preventDefault();
            event.stopImmediatePropagation();
            event.stopPropagation();
            return false;
        } else {
            confirma = confirm('¿Esta seguro que desea elminar el elemento?');
            
            if(!confirma) {
                event.preventDefault();
                event.stopImmediatePropagation();
                event.stopPropagation();
                return false;
            }
        }  
    });
    
});
</script>

<style>
    select[name=raiz] {
        margin-bottom: auto !important;
    }
</style>

<?php
    $showInsert = Menubuilder::isGranted(DIR_SIIGS.'::menu::insert');
    $showUpdate = Menubuilder::isGranted(DIR_SIIGS.'::menu::update');
    $showDelete = Menubuilder::isGranted(DIR_SIIGS.'::menu::delete');
    $showView   = Menubuilder::isGranted(DIR_SIIGS.'::menu::view');
    
    echo '<h2>'.$title.'</h2>';
    
    if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
?>

<div id="menuTree">
    <?php echo $menuTree; ?>
</div>

<br />
<?php
    if($showInsert)
        echo ' <a id="crear" data-ruta="'.site_url().DIR_SIIGS.'/menu/insert/" href="'.site_url().DIR_SIIGS.'/menu/insert/" class="btn btn-primary">Crear nuevo</a>';

    if($showView) 
        echo ' <a id="detalles" data-ruta="'.site_url().DIR_SIIGS.'/menu/view/" href="" class="btn btn-primary">Detalles</a>';
    
    if($showUpdate) 
        echo ' <a id="modificar" data-ruta="'.site_url().DIR_SIIGS.'/menu/update/" href="" class="btn btn-primary">Modificar</a>';
    
    if($showDelete)
        echo ' <a id="eliminar" data-ruta="'.site_url().DIR_SIIGS.'/menu/delete/" href="" class="btn btn-primary">Eliminar</a>';
?>
