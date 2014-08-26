<link href="/resources/themes/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<script src="/resources/ui/jquery-ui-1.8.17.custom.js" type="text/javascript"></script>	
<script type="text/javascript">

$(document).ready(function(){
    $('#btnFiltrar').click(function(e){
        // Eliminar la pagina de la url del action
        action = $('#form_filter_bitacora').attr('action');
        action = action.replace(/\d+(\/)*$/,'');

        $('#form_filter_bitacora').attr('action',action);
        $('#form_filter_bitacora').submit();
    });

    $("#fechaIni").datepicker(optionsFecha);
    $("#fechaFin").datepicker(optionsFecha);
    
    $("#limpiaFecha").click(function(){
        $("#fechaIni").val('');
        $("#fechaFin").val('');
    });
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
$opcion_insert = Menubuilder::isGranted(DIR_TES.'::notificacion::insert');
$opcion_view = Menubuilder::isGranted(DIR_TES.'::notificacion::view');
$opcion_update = Menubuilder::isGranted(DIR_TES.'::notificacion::update');
$opcion_delete = Menubuilder::isGranted(DIR_TES.'::notificacion::delete');
?>
<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>'; ?>
<fieldset style="width: 50%">
<?php echo form_open(DIR_TES.'/notificacion/index/'.$pag, array('name'=>'form_filter_bitacora', 'id'=>'form_filter_bitacora')); ?>
<input type="hidden" name="filtrar" value="true" />
Buscar:<input type="text" name="busqueda" value="<?php echo set_value('busqueda', ''); ?>" />
<br><u>(Rango de fechas en que inician las notificaciones a buscar)</u>
<br>Desde:<input type="text" id="fechaIni" name="fechaIni" value="<?php echo isset($fechaIni) ? $fechaIni: ''; ?>" size="8" placeholder="desde" />
<br>Hasta:<input type="text" id="fechaFin" name="fechaFin" value="<?php echo isset($fechaFin) ? $fechaFin: ''; ?>" size="8" placeholder="hasta" />
<p>
<button id="btnFiltrar" class="btn btn-small btn-primary btn-icon">Buscar <i class="icon-search"></i></button>
<input type="button" value="Limpiar Fechas" id="limpiaFecha" class="btn btn-mini btn-primary" />
</p>
</form>
</fieldset>
<?php if($opcion_insert) { ?><a href="/<?php echo DIR_TES?>/notificacion/insert" class="btn btn-small btn-primary btn-icon">Crear nuevo <i class="icon-plus"></i></a><?php } ?>
<div class="table table-striped">
<table>
<thead>
	<tr>
		<th><h2>Título</h2></th>
		<th><h2>Contenido</h2></th>
		<th><h2>Reportar a tabletas</h2></th>
		<th><h2>Desde</h2></th>
		<th><h2>Hasta</h2></th>
		<?php if($opcion_view) { ?><th><h2></h2></th><?php } ?>
		<?php if($opcion_update) { ?><th><h2></h2></th><?php } ?>
		<?php if($opcion_delete) { ?><th><h2></h2></th><?php } ?>
	</tr>
</thead>
<tbody>
	<?php if (isset($notifications)) foreach ($notifications as $notification_item): ?>
	<tr>
		<td><?php echo $notification_item->titulo ?></td>
		<td><?php echo $notification_item->contenido ?></td>
		<td><?php foreach($notification_item->tabletas as $tableta){
			echo $tableta.'<br>';
		} ?></td>
		<td><?php $time = strtotime($notification_item->fecha_inicio);
			echo date('d/m/Y', $time);
			 ?></td>
		<td><?php $time = strtotime($notification_item->fecha_fin);
			echo date('d/m/Y', $time);
			 ?></td>
		<?php if($opcion_view) { ?><td><a id="detalles" href="/<?php echo DIR_TES?>/notificacion/view/<?php echo $notification_item->id ?>" class="btn btn-small btn-primary btn-icon">Detalles <i class="icon-eye-open"></a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_TES?>/notificacion/update/<?php echo $notification_item->id ?>" class="btn btn-small btn-primary btn-icon">Modificar <i class="icon-pencil"></a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_TES?>/notificacion/delete/<?php echo $notification_item->id ?>"  class="btn btn-small btn-primary btn-icon" onclick="if (confirm('¿Realmente desea eliminar esta notificación?')) { return true; } else {return false;}">Eliminar <i class="icon-remove"></a></td><?php } ?>
	</tr>
</tbody>
	<?php endforeach ?>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
</div>