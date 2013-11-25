<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($catalogo_item))
{
echo '<h2>[ '.$catalogo_item->nombre.' ]</h2>';
$campos = explode('||', $catalogo_item->campos);
$llaves = explode('||', $catalogo_item->llave);
echo "<table><thead><tr><th colspan = 4>Campos del catalogo</td></tr></thead>";
echo '<tr><td>Nombre</td><td>Tipo de dato</td><td>Nulo</td><td>Llave primaria</td></tr>';
foreach ($campos as $campo)
{
	//var_dump($campo);
	$datos = explode('|', $campo);
	echo '<tr><td>'.$datos[0]. '</td><td>' . $datos[1]. '</td><td>' . $datos[2]. '</td><td>' . $datos[3].'</tr>';
}
foreach ($llaves as $campo)
{
	//var_dump($campo);
	$datos = explode('|', $campo);
	echo '<tr><td>'.$datos[0]. '</td><td>' . $datos[1]. '</td><td>' . $datos[2]. '</td><td>' . $datos[3].'</tr>';
}
echo '</table>';
}
else
{
	echo "No se ha encontrado el elemento";
}