<link href="/resources/themes/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<script src="/resources/ui/jquery-ui-1.8.17.custom.js" type="text/javascript"></script>	
<script type="text/javascript">
var objFecha = new Date();

var optionsFecha = {
    changeMonth: true,
    changeYear: true,
    duration: "fast",
    dateFormat: 'dd-mm-yy',
    constrainInput: true,
    firstDay: 1,
    closeText: 'X',
    showOn: 'both',
    buttonImage: '/resources/images/calendar.gif',
    buttonImageOnly: true,
    buttonText: 'Clic para seleccionar una fecha',
    yearRange: '2005:'+objFecha.getFullYear(),
    showButtonPanel: false
};

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
        echo $msgResult.'<br /><br />'; ?>
<fieldset style="width: 50%">
    <legend>Opciones de búsqueda</legend>
<?php echo form_open(DIR_TES.'/notificacion/index/'.$pag, array('name'=>'form_filter_bitacora', 'id'=>'form_filter_bitacora')); ?>
<p><input type="hidden" name="filtrar" value="true" />
<table>
<tr>
<td>Buscar</td>
<td colspan='4'> <input type="text" name="busqueda" value="<?php echo set_value('busqueda', ''); ?>" /></td>
</tr>
<tr>
<td>Fecha Inicio:</td><td><input type="text" id="fechaIni" name="fechaIni" value="<?php echo isset($fechaIni) ? $fechaIni: ''; ?>" /></td>
<td>Fecha Fin:</td><td><input type="text" id="fechaFin" name="fechaFin" value="<?php echo isset($fechaFin) ? $fechaFin: ''; ?>" /></td>
</tr>
<tr>
<td colspan='2'>
<input type="submit" name="btnFiltrar" id="btnFiltrar" value="Buscar" /></td>
<td colspan='2'>
<input type="button" value="Limpiar Fechas" id="limpiaFecha" /></td>
</tr>
</table>
</form>
</fieldset>
<br />
<table border="1">
	<tr>
		<th>Título</th>
		<th>Contenido</th>
		<th>Reportar a tabletas</th>
		<th>Desde</th>
		<td>Hasta</td>
		<?php if($opcion_view) { ?><td></td><?php } ?>
		<?php if($opcion_update) { ?><td></td><?php } ?>
		<?php if($opcion_delete) { ?><td></td><?php } ?>
	</tr>
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
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_TES?>/notificacion/view/<?php echo $notification_item->id ?>">Ver detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_TES?>/notificacion/update/<?php echo $notification_item->id ?>">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_TES?>/notificacion/delete/<?php echo $notification_item->id ?>" onclick="if (confirm('Realmente desea eliminar esta notificaci�n?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
	</tr>
	<?php endforeach ?>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
<?php if($opcion_insert) { ?><a href="/<?php echo DIR_TES?>/notificacion/insert">Crear nuevo</a><?php } ?>