<?php
if(!empty($msgResult))
    echo '<h3>'.$msgResult.'</h3>';

echo '<h2>'.$title.'</h2>';

echo validation_errors();

echo form_open(site_url().DIR_TES.'/tableta/insert/'); ?>
<table>
    <tr><td>MAC: </td><td><?php echo form_input( array('name'=>'mac', 'maxlength'=>'20') ); ?> </td></tr>
    <tr><td colspan="2"><input type="submit" value="Guardar" />
        <input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_TES; ?>/tableta/'" /></td></tr>
</table>
</form>
<a href="<?php echo site_url().DIR_TES; ?>/tableta">Regresar al listado</a>