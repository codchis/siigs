<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($datos))
{
?>
<table>
<thead>
	<tr>
	<th>CIE10</th>
	<th>Descripción</th>
        <th>Activo</th>
	</tr>
</thead>
<?php foreach ($datos as $item): ?>
	<tr>
		<td><?php echo $item->id_cie10 ?></td>
		<td><?php echo $item->descripcion ?></td>
                <td>
                    <input class="check_catalogo" type="checkbox" id="<?php echo $item->id;?>" catalogo="eda" <?php echo ($item->activo == false) ? "" : "checked" ; ?> >
                    <?php echo ($item->activo == false) ? "Activar" : "Desactivar" ; ?>
                </td>
	</tr>
<?php endforeach ?>
</table>
<?php
}
else
{
	echo "No hay datos registrados en el catálogo";
}