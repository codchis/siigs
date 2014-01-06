<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($accion_item))
{
    echo '<div class="table table-striped">
        <table>
        <tr><td><strong>Acci&oacute;n</strong></td><td>'.$accion_item->nombre.'</td></tr>
        <tr><td><strong>Descripci&oacute;n</strong></td><td>'.$accion_item->descripcion.'</td></tr>
        <tr><td><strong>M&eacute;todo</strong></td><td>'.$accion_item->metodo.'</td></tr>
    </table></div>';
}
else
{
	echo '<div class="error">Registro no encontrado</div>';
}