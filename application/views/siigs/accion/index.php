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
<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/accion/insert" class="btn btn-primary">Crear Nuevo</a><?php } ?>
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
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/accion/view/<?php echo $accion_item->id ?>" id="detalles" class="btn btn-small btn-primary">Detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/accion/update/<?php echo $accion_item->id ?>"  class="btn btn-small btn-primary">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/accion/delete/<?php echo $accion_item->id ?>"  class="btn btn-small btn-primary" onclick="if (confirm('Realmente desea eliminar esta acción?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
	</tr>
<?php endforeach ?>

<?php } else {?>
<tr>
	<th colspan="6">No se encontraron registros</th>
</tr>
<?php } ?>
</table>
</div>