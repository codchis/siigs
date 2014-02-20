<?php
if(!empty($msgResult))
    echo '<h3>'.$msgResult.'</h3>';

echo '<h2>'.$title.'</h2>';

echo validation_errors();

echo form_open(site_url().DIR_TES.'/tableta/insert/'); ?>
<table>
    <tr><td>MAC: </td><td><?php echo form_input( array('name'=>'mac', 'maxlength'=>'20') ); ?> </td></tr>
    <tr><td colspan="2"><button type="submit">Guardar <i class="icon-hdd"></i></button>
        <button type="button" name="cancelar" onclick="location.href='<?php echo site_url().DIR_TES; ?>/tableta/'" >Cancelar <i class="icon-arrow-left"></i></button></td></tr>
</table>
</form>