<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($raiz_item))
{
echo '<h5>Descripci&oacute;n: '.$raiz_item->descripcion.'</h5><br/>';

?>
<div class="table table-striped">
<table id="raizcatalogo">
	<thead>
		<tr>
			<th colspan=5><h2>Catálogos de la raiz</h2></th>
		</tr>
		<tr>
			<td><h2>Nivel</h2></td>
			<td><h2>Catálogo</h2></td>
			<td><h2>Llave</h2></td>
			<td><h2>Descripción</h2></td>
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
</div>
<?php
}
else
{
	echo "No se ha encontrado el elemento";
}
?>