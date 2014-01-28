<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/grupo/update/'.$group_item->id) ?>
<div class="table table-striped">
<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><?php echo $group_item->nombre ?></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripción</label></td>
		<td><textarea name="descripcion"><?php echo $group_item->descripcion; ?></textarea></td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="hidden" name="id" value="<?php echo $group_item->id; ?>"/>
		<button type="submit" name="submit" id="guardar" class="btn btn-small btn-primary btn-icon" >Guardar <i class="icon-hdd"></i></button>
		<button type="button"  onclick="window.location.href='/<?php echo DIR_SIIGS?>/grupo/'" class="btn btn-small btn-primary btn-icon">Cancelar <i class="icon-arrow-left"></i></button>
		<td>
	</tr>
</table>
</div>
</form>