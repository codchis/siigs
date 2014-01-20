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
$opcion_insert = Menubuilder::isGranted(DIR_SIIGS.'::raiz::insert');
$opcion_view = Menubuilder::isGranted(DIR_SIIGS.'::raiz::view');
$opcion_update = Menubuilder::isGranted(DIR_SIIGS.'::raiz::update');
$opcion_delete = Menubuilder::isGranted(DIR_SIIGS.'::raiz::delete');
?>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
 <?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/raiz/insert" class="btn btn-primary">Crear Nuevo</a><?php } ?>
<div class="table table-striped">
<table>
<thead>
	<tr>
	<th><h2>Descripci&oacute;n</h2></th>
	<?php if($opcion_view) { ?><th></th><?php } ?>
	<?php if($opcion_update) { ?><th></th><?php } ?>
	<?php if($opcion_delete) { ?><th></th><?php } ?>
	</tr>
</thead>
<?php if ( !empty($raices) && !count($raices) == 0) { ?>
<?php foreach ($raices as $raiz_item): ?>
	<tr>
		<td><?php echo $raiz_item->descripcion ?></td>
		<?php if($opcion_view) { ?><td><a id="detalles" href="/<?php echo DIR_SIIGS; ?>/raiz/view/<?php echo $raiz_item->id ?>" class="btn btn-primary">Detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/raiz/update/<?php echo $raiz_item->id ?>" class="btn btn-primary">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/raiz/delete/<?php echo $raiz_item->id ?>"  class="btn btn-primary"onclick="if (<?php echo $raiz_item->catalogos?>=='0'){if (confirm('Realmente desea eliminar esta raiz?')) { return true; } else {return false;}} else {alert('No se puede eliminar la raiz porque ya contiene catalogos asociados.') ; return false;}">Eliminar</a></td><?php } ?>
	</tr>
<?php endforeach ?>
<?php } else {?>
<thead>
<tr>
	<th colspan=4>No se encontraron registros</th>
</tr>
</thead>
<?php } ?>
</table>
</div>