<h2><?=$title;?></h2>
<br />
<?php
    if(!empty($msgResult))
        echo '<strong>'.$msgResult.'</strong>';
?>
<br />

<?php echo form_open(site_url().DIR_TES.'/tableta/', array('onsubmit'=>"return confirm('Esta seguro de eliminar los elementos seleccionados');")); ?>

<table border="1">
    <thead>
        <tr>
            <th></th>
            <th>MAC</th>
            <th>Versi&oacute;n</th>
            <th>Ultima Actualizaci&oacute;n</th>
            <th>Status</th>
            <th>Tipo Censo</th>
            <th>Unidad Médica</th>
            <th>Usuarios</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($registros)) {
            foreach ($registros as $fila) {
                echo '<tr id="'.$fila->id.'">
                    <td><input type="checkbox" name="registroEliminar[]" value="'.$fila->id.'" /></td>
                    <td>'.$fila->mac.'</td>
                    <td>'.$fila->version.'</td>
                    <td>'.htmlentities(formatFecha($fila->ultima_actualizacion)).'</td>
                    <td>'.htmlentities($fila->status).'</td>
                    <td>'.htmlentities($fila->tipo_censo).'</td>
                    <td>'.htmlentities($fila->id_asu_um).'</td>
                    <td>'.($fila->usuarios_asignados==0 ? 'No asignados' : '<a href="'.site_url().DIR_TES.'/usuario_tableta/index/'.$fila->id.'">Ver</a>').'</td>
                    <td><a href="'.site_url().DIR_TES.'/tableta/view/'.$fila->id.'">Ver</a></td>
                    <td><a href="'.site_url().DIR_TES.'/tableta/update/'.$fila->id.'">Modificar</a></td>
                    <td><a href="'.site_url().DIR_TES.'/tableta/delete/'.$fila->id.'"
                        onclick="if(confirm(\'Realmente desea eliminar el registro\')) { return true; } else { return false; }">Eliminar</a></td>
                </tr>';
            }
        } else {
            echo '<tr><td colspan="7"><div align="center">No se encontraron registros en la busqueda</div></td></tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>

<input type="submit" value="Eliminar Seleccionados" />

</form>
<br />

<input type="button" name="crear" value="Crear nuevo" onclick="location.href='<?php echo site_url().DIR_TES; ?>/tableta/insert'" />

<br /><br />

<label>Subir un archivo con la lista de direcciones MAC</label>
<?php echo form_open_multipart(site_url().DIR_TES.'/tableta/uploadFile');?>
    <input type="file" name="archivo" size="60" />
    <input type="submit" value="Subir Archivo" />
</form>