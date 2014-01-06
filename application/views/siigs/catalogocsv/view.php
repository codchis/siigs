<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($catalogo_item))
{
echo '<h2>[ '.$catalogo_item->nombre.' ]</h2>';
$campos = explode('||', $catalogo_item->campos);
$llaves = explode('||', $catalogo_item->llave);
echo "<div class='table table-striped'><table><thead><tr><th colspan = 4><h2>Campos del catalogo</h2></td></tr></thead>";
echo '<tr><td>Nombre</td><td>Tipo de dato</td><td>Nulo</td><td>Llave primaria</td></tr>';
foreach ($llaves as $campo)
{
	//var_dump($campo);
	$datos = explode('|', $campo);
	echo '<tr><td>'.$datos[0]. '</td><td>' . $datos[1]. '</td><td>' . $datos[2]. '</td><td>' . $datos[3].'</tr>';
}
foreach ($campos as $campo)
{
	//var_dump($campo);
	$datos = explode('|', $campo);
	echo '<tr><td>'.$datos[0]. '</td><td>' . $datos[1]. '</td><td>' . $datos[2]. '</td><td>' . $datos[3].'</tr>';
}
echo '</table></div>';

if (!empty($datos_cat))
{
    if (count($datos_cat)>0)
    {
        echo "<div class='table table-striped'><table><thead><tr><th colspan = ".count($datos[0]).">Datos del catalogo</td></tr></thead>";
        foreach ($datos_cat as $dato)
        {
            echo "<tr>";
            foreach($dato as $col)
                echo "<td>".$col."</td>";
            echo "</tr>";
        }
        echo "</table></div'>";
    }
}

}
else
{
	echo "No se ha encontrado el elemento";
}