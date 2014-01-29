<script>
$(document).ready(function(){
  	$("a#detalles").fancybox({
		'width'             : '50%',
		'height'            : '60%',				
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'type'			: 'iframe',									
	});  
});
</script>

<?php 
$opcion_insert = Menubuilder::isGranted(DIR_SIIGS.'::accion::insert');
$opcion_view = Menubuilder::isGranted(DIR_SIIGS.'::accion::view');
$opcion_update = Menubuilder::isGranted(DIR_SIIGS.'::accion::update');
$opcion_delete = Menubuilder::isGranted(DIR_SIIGS.'::accion::delete');
?>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<br/>
<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/accion/insert" class="btn btn-primary">Crear Nuevo<i class="icon-plus"></i></a><br/><br/><?php } ?>
<div class="table table-striped">
<table>
<thead>
	<tr>
	<th>Nombre</th>
	<th>Descripci&oacute;n</th>
	<th>M&eacute;todo</th>
	<?php if($opcion_view) { ?><th></th> <?php } ?>
	<?php if($opcion_update) { ?><th></th> <?php } ?>
	<?php if($opcion_delete) { ?><th></th> <?php } ?>
	</tr>
</thead>

<?php if ( !empty($acciones) && !count($acciones) == 0) { ?>
<?php foreach ($acciones as $accion_item): ?>
	<tr>
		<td><?php echo $accion_item->nombre ?></td>
		<td><?php echo $accion_item->descripcion ?></td>
		<td><?php echo $accion_item->metodo ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/accion/view/<?php echo $accion_item->id ?>" id="detalles" class="btn btn-small btn-primary btn-icon">Detalles<i class="icon-eye-open"></i></a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/accion/update/<?php echo $accion_item->id ?>"  class="btn btn-small btn-primary btn-icon">Modificar<i class="icon-pencil"></i></a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/accion/delete/<?php echo $accion_item->id ?>"  class="btn btn-small btn-primary btn-icon" onclick="if (confirm('Realmente desea eliminar esta acción?')) { return true; } else {return false;}">Eliminar<i class="icon-remove"></i></a></td><?php } ?>
	</tr>
<?php endforeach ?>

<?php } else {?>
<tr>
	<th colspan="6">No se encontraron registros</th>
</tr>
<?php } ?>
<tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
</tfoot>
</table>
</div>