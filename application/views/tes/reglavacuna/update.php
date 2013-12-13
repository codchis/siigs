<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
 <?php
if (!empty($regla_item))
{
?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_TES.'/reglavacuna/update/'.$regla_item->id) ?>
<table>
	<tr>
		<td><label for="nombre">Vacuna:</label></td>
                <td colspan="2"><?php  echo  form_dropdown('id_vacuna', $vacunas, $regla_item->id_vacuna); ?></td>
	</tr>
	<tr>
		<td><label for="nombre">Vacuna previa:</label></td>
                <td colspan="2"><?php  echo  form_dropdown('id_vacuna_previa', $vacunas, $regla_item->id_vacuna_secuencial); ?></td>
	</tr>
	<tr>
		<td><label for="descripcion">Tipo de Aplicaci&oacute;n:</label></td>
                <td><input type="radio" name="tipo_aplicacion" value="nacimiento" <?php if ($regla_item->aplicacion=='Nacimiento') echo "checked"; ?>/> A partir de nacimiento</td>
                <td><input type="radio" name="tipo_aplicacion" value="previa" <?php if ($regla_item->aplicacion=='Secuencial') echo "checked"; ?>/> A partir de vacuna previa</td>
	</tr>
	<tr>
                <td><label for="descripcion">D&iacute;as de aplicaci&oacute;n:</label></td>
                <td>Desde:<br/><input type="text" name="aplicacion_inicio" value="<?php echo $regla_item->desde;?>"/></td>
                <td>Hasta:<br/><input type="text" name="aplicacion_fin" value="<?php echo $regla_item->hasta;?>"/></td>
	</tr>
	<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $regla_item->id; ?>"/>
		<input type="submit" name="submit" value="Guardar" />
		<td>
	</tr>
</table>
</form>
<?php
}
else
{
echo "No se ha encontrado el elemento";
}
?>