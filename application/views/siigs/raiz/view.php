<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($raiz_item))
{
echo '<h5>Descripci&oacute;n: '.$raiz_item->descripcion.'</h5><br/>';

?>
<table id="raizcatalogo">
	<thead>
		<tr>
			<th colspan=5>Catálogos de la raiz</th>
		</tr>
		<tr>
			<td>Nivel</td>
			<td>Catálogo</td>
			<td>Llave</td>
			<td>Descripción</td>
		</tr>
		<?php foreach ($catalogos as $item):?>
			<tr>
				<td><?php echo $item->grado_segmentacion ?></td>
				<td><?php echo $item->tabla_catalogo ?></td>
				<td><?php echo $item->nombre_columna_llave ?></td>
				<td><?php echo $item->nombre_columna_descripcion ?></td>
			</tr>
		<?php endforeach ?>
	</thead>
</table>

<?php
}
else
{
	echo "No se ha encontrado el elemento";
}
?>