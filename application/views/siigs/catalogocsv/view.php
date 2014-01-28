<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<h2><?php echo $title; ?></h2>
<a href="<?php echo site_url().DIR_SIIGS; ?>/catalogocsv/" class="btn btn-primary">Regresar al listado<i class="icon-arrow-left"></i></a>
<br/>
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

echo 'Comentarios: <textarea readonly>'.$catalogo_item->comentario.'</textarea><br/><br/>';

if (!empty($datos_cat))
{
    if (count($datos_cat)>0)
    {
        echo "<div class='table table-striped'><table><thead><tr><th colspan = ".count((array)$datos_cat[0]).">Datos del catalogo</td></tr>";
        ?>
        <tr>
        <?php foreach(array_keys((array)$datos_cat[0]) as $claves) {
            if ($claves != 'activo') {?>
            <td><h2><?php echo $claves;?></h2></td>
            <?php } } ?>
        </tr>
        <?php
        echo"</thead>";
        foreach ($datos_cat as $dato)
        {
            echo "<tr>";
            foreach($dato as $col)
                echo "<td>".$col."</td>";
            echo "</tr>";
        }
        
        echo "<tfoot><tr><td colspan='7'>
            <div id='paginador' align='center'>".$this->pagination->create_links()."</div>
        </td></tr></tfoot></table></div'>";
    }
}

}
else
{
	echo "No se ha encontrado el elemento";
}