<script type="text/javascript">
$(document).ready(function(){
    $('#btnFiltrar').click(function(e){
        // Eliminar la pagina de la url del action
        action = $('#form_filter_bitacora').attr('action');
        action = action.replace(/\d+(\/)*$/,'');

        $('#form_filter_bitacora').attr('action',action);
        $('#form_filter_bitacora').submit();
    });
});
</script>
<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo $msgResult.'<br /><br />'; ?>
<fieldset style="width: 50%">
    <legend>Opciones de b√∫squeda</legend>
<?php echo form_open(DIR_TES.'/notificacion/index/'.$pag, array('name'=>'form_filter_bitacora', 'id'=>'form_filter_bitacora')); ?>
<p><input type="hidden" name="filtrar" value="true" />
<table>
<tr>
<td>Buscar</td><td colspan='2'> <input type="text" name="busqueda" value="<?php echo set_value('busqueda', ''); ?>" /></td>
</tr>
<tr>
<td>
Fecha Inicio:</td><td><input type="date" name="fechaIni" value="<?php echo isset($fechaIni) ? $fechaIni: ''; ?>" /></td>
             <td><input type="date" name="fechaFin" value="<?php echo isset($fechaFin) ? $fechaFin: ''; ?>" /></td>
             </tr>
<tr>
<td colspan='3'>
<input type="submit" name="btnFiltrar" id="btnFiltrar" value="Buscar" /></td>
</tr>
</table>
</form>
</fieldset>
<br />
<table border="1">
	<tr>
		<th>TÌtulo</th>
		<th>Contenido</th>
		<th>Reportar a tabletas</th>
		<th>Desde</th>
		<td>Hasta</td>
		<td></td>
		<td></td>
		<td></td>
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
		<td><a href="/<?php echo DIR_TES?>/notificacion/view/<?php echo $notification_item->id ?>">Ver detalles</a></td>
		<td><a href="/<?php echo DIR_TES?>/notificacion/update/<?php echo $notification_item->id ?>">Modificar</a></td>
		<td><a href="/<?php echo DIR_TES?>/notificacion/delete/<?php echo $notification_item->id ?>" onclick="if (confirm('Realmente desea eliminar esta notificaciÛn?')) { return true; } else {return false;}">Eliminar</a></td>
	</tr>
	<?php endforeach ?>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
<a href="/<?php echo DIR_TES?>/notificacion/insert">Crear nuevo</a>