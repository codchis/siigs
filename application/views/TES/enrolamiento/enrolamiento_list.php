<script type="text/javascript">
$(document).ready(function(){
    $('#paginador a').click(function(e){
        e.preventDefault();
        pag = $(this).attr('href');
        $('#form_filter_bitacora').attr('action', pag);
        $('#form_filter_bitacora').submit();
    });

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
<?php 
if(!empty($msgResult))
	echo "<div class='".$this->session->flashdata('infoclass')."'>".$msgResult."</div>";
?>
<fieldset style="width: 50%">
    <legend>Opciones de búsqueda</legend>
<?php echo form_open(DIR_TES.'/enrolamiento/index/'.$pag, array('name'=>'form_filter_bitacora', 'id'=>'form_filter_bitacora')); ?>
Buscar usuario
<input type="text" name="busqueda" value="<?php echo set_value('busqueda', ''); ?>" /> 
<input type="submit" name="btnFiltrar" id="btnFiltrar" value="Buscar" />
</form>
</fieldset>
<br />
<table border="1">
	<tr>
		<th>CURP</th>
		<th>Nombre</th>
		<th>Ap. Paterno</th>
		<th>Ap. Materno</th>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<?php if (isset($users)) foreach ($users as $user_item): ?>
	<tr>
		<td><?php echo $user_item->curp ?></td>
		<td><?php echo $user_item->nombre ?></td>
		<td><?php echo $user_item->apellido_paterno ?></td>
		<td><?php echo $user_item->apellido_materno ?></td>
		<td><a href="/<?php echo DIR_TES?>/enrolamiento/view/<?php echo $user_item->id ?>">Ver detalles</a></td>
		<td><a href="/<?php echo DIR_TES?>/enrolamiento/update/<?php echo $user_item->id ?>">Modificar</a></td>
		<td><a href="/<?php echo DIR_TES?>/enrolamiento/delete/<?php echo $user_item->id ?>" onclick="if (confirm('Realmente desea eliminar este usuario?')) { return true; } else {return false;}">Eliminar</a></td>
	</tr>
	<?php endforeach ?>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
<a href="/<?php echo DIR_TES?>/enrolamiento/insert">Crear nuevo</a>