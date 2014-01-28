<script>
$(document).ready(function(){
    obligatorios('frm_insert');
});
</script>

<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
 <?php
if (!empty($controlador_item))
{
?>
<?php echo validation_errors(); ?>
<div class="table table-striped">
<?php echo form_open(DIR_SIIGS.'/controlador/update/'.$controlador_item->id, array('id'=>'frm_insert', 'onclick' => 'limpiaformulario(\'frm_insert\')','onkeyup' => 'limpiaformulario(\'frm_insert\')')) ?>

    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div> 
    
    <table>
    	<tr>
		<td><label for="id_entorno">Entorno</label></td>
		<td>  <?php  echo  form_dropdown('id_entorno', $entornos, $controlador_item->id_entorno, 'title=\'requiere\''); ?></td>
	</tr>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" title='requiere' required value="<?php echo $controlador_item->nombre; ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripcion</label></td>
		<td><textarea name="descripcion" title='requiere' required><?php echo $controlador_item->descripcion; ?></textarea></td>
	</tr>
	<tr>
		<td><label for="clase">Clase</label></td>
		<td><textarea name="clase" title='requiere' required><?php echo $controlador_item->clase; ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $controlador_item->id; ?>"/>
                <button type="submit" name="submit" class="btn btn-primary" onclick="return validarFormulario('frm_insert')">Guardar<i class="icon-hdd"></i></button>
                <button type="button" name="cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/controlador/'" class="btn btn-primary">Cancelar<i class="icon-arrow-left"></i></button>
		</td>
	</tr>
</table>
</form>
<?php
}
else
{
echo '<div class="error">Registro no encontrado</div>';
}
?>
</div>