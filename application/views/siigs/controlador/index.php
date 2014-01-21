<script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>

<script>
$(document).ready(function(){
  	$("a#detalles").fancybox({
		'width'             : '50%',
		'height'            : '60%',				
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'type'			: 'iframe',									
	});  

    $('#paginador a').click(function(e){
        e.preventDefault();
        pag = $(this).attr('href');
        $('#form_filter_controlador').attr('action', pag);
        
        $('#form_filter_controlador').submit();
    });

    $('#btnFiltrar').click(function(e){
        // Eliminar la pagina de la url del action
        action = $('#form_filter_controlador').attr('action');
        action = action.replace(/\d+(\/)*$/,'');

        $('#form_filter_controlador').attr('action',action);
        $('#form_filter_controlador').submit();
    });
    
});

</script>

<?php 
$opcion_accion = Menubuilder::isGranted(DIR_SIIGS.'::controlador::accion');
$opcion_insert = Menubuilder::isGranted(DIR_SIIGS.'::controlador::insert');
$opcion_view = Menubuilder::isGranted(DIR_SIIGS.'::controlador::view');
$opcion_update = Menubuilder::isGranted(DIR_SIIGS.'::controlador::update');
$opcion_delete = Menubuilder::isGranted(DIR_SIIGS.'::controlador::delete');
?>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<?php if (!empty($controladores) && !count($controladores) == 0) { ?>

<script type="text/javascript">

 function buscar(obj)
 {
	 val = $('select[name="entorno"]').val();
	 if (val == 0)
		 return false;
		 //window.open("/controlador/","_self");
	 else
		 return true;
		// window.open("/entorno/" + val + "/controlador/","_self");
 }

</script>
<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/controlador/insert/<?php echo $id_entorno;?>" class="btn btn-primary">Crear Nuevo</a><?php } ?>

<fieldset>
    <legend><strong>Opciones de filtrado</strong></legend>
 		<?php echo form_open(DIR_SIIGS.'/controlador/index/'.$pag, array('name'=>'form_filter_controlador', 'id'=>'form_filter_controlador')); ?>
	         Entorno:
	        <?php  echo  form_dropdown('id_entorno', $entornos, $id_entorno); ?>
	        <input type="submit" name="btnFiltrar" id="btnFiltrar" value="Filtrar" onclick="return buscar();" class="btn btn-primary"/>
   		</form>
</fieldset>
<div class="table table-striped">
<table>
<thead>
	<tr>
	<th>Entorno</th>
	<th>Nombre</th>
	<th>Descripci&oacute;n</th>
	<th>Clase</th>
	<?php if($opcion_view) { ?><th></th><?php } ?>
	<?php if($opcion_accion) { ?><th></th><?php } ?>
	<?php if($opcion_update) { ?><th></th><?php } ?>
	<?php if($opcion_delete) { ?><th></th><?php } ?>
	</tr>
</thead>
<?php if ( !empty($controladores) && !count($controladores) == 0) { ?>
<?php foreach ($controladores as $controlador_item): ?>
	<tr>
	<td><a href="/<?php echo DIR_SIIGS; ?>/entorno/view/<?php echo $controlador_item->id_entorno ?>"><?php echo $controlador_item->entorno;?></a></td>
		<td><?php echo $controlador_item->nombre ?></td>
		<td><?php echo $controlador_item->descripcion ?></td>
		<td><?php echo $controlador_item->clase ?></td>
		<?php if($opcion_view) { ?><td><a id="detalles" href="/<?php echo DIR_SIIGS; ?>/controlador/view/<?php echo $controlador_item->id ?>" class="btn btn-small btn-primary">Detalles</a></td><?php } ?>
		<?php if($opcion_accion) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/controlador/accion/<?php echo $controlador_item->id ?>" class="btn btn-small btn-primary">Acciones</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/controlador/update/<?php echo $controlador_item->id ?>" class="btn btn-small btn-primary">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/controlador/delete/<?php echo $controlador_item->id ?>" class="btn btn-small btn-primary" onclick="if (confirm('Realmente desea eliminar este controlador?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
		<td></td>
	</tr>
<?php endforeach ?>
<tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
</tfoot>
<?php } } else {?>
<thead>
<tr>
    <th colspan="8">No se encontraron registros</th>
</tr>
</thead>
<?php } ?>
</table></div>
