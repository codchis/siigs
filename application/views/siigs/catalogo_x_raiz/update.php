<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<div class="table table-striped">
 <?php
if (!empty($raiz_item))
{
?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_SIIGS.'/raiz/update/'.$raiz_item->id) ?>
<table>
	<tr>
		<td><label for="descripcion">Descripci&oacute;n</label></td>
		<td><textarea name="descripcion"><?php echo $raiz_item->descripcion; ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2>
			<table id="raizcatalogo">
				<thead>
					<tr>
                                            <th><input type="button" id="btnagregar" value="Agregar" class="btn btn-primary"></th>
						<th colspan=4>Catálogos de la raiz</th>
					</tr>
					<tr>
						<td>Nivel</td>
						<td>Catálogo</td>
						<td>Llave</td>
						<td>Descripción</td>
					</tr>
				</thead>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $raiz_item->id; ?>"/>
                <input type="submit" name="submit" value="Guardar" class="btn btn-primary" />
		<td>
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