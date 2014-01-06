<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($controlador_item))
{
        echo '<div class="table table-striped">
        <table>
        <tr><td><strong>Nombre</strong></td><td>'.$controlador_item->nombre.'</td></tr>
        <tr><td><strong>Descripci&oacute;n</strong></td><td>'.$controlador_item->descripcion.'</td></tr>
        <tr><td><strong>Clase</strong></td><td>'.$controlador_item->clase.'</td></tr>
    </table></div>';
}
else
	echo '<div class="error">Registro no encontrado</div>';
?>
<a href="<?php echo site_url().DIR_SIIGS; ?>/controlador/" class="btn btn-primary">Regresar al listado</a>