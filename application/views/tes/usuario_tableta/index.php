<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">

<style type="text/css">
    label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>

<?php
$showInsert = Menubuilder::isGranted(DIR_TES.'::usuario_tableta::insert');
$showDelete = Menubuilder::isGranted(DIR_TES.'::usuario_tableta::delete');
    
if(!empty($msgResult))
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';

echo form_open(site_url().DIR_TES.'/usuario_tableta/index/'.$tableta->id, array('onsubmit'=>"return confirm('¿Esta seguro de eliminar los elementos seleccionados?');")); 

echo '<center><strong>Datos generales de la tableta</strong></center>

<div class="table table-striped" align="center">
<table align="center">
    <tr>
        <td>Direcci&oacute;n MAC</td>
        <td>Versi&oacute;n</td>
        <td>Ultima actualizaci&oacute;n</td>
        <td>Status</td>
        <td>Tipo Censo</td>
    </tr>
    <tr>
        <td>'.$tableta->mac.'</td>
        <td>'.$tableta->id_version.'</td>
        <td>'.formatFecha($tableta->ultima_actualizacion).'</td>
        <td>'.$tableta->status.'</td>
        <td>'.$tableta->tipo_censo.'</td>
    </tr>
</table>
</div>';
?>

<h2><?=$title;?></h2>

<div class="table table-striped">
<table>
    <thead>
        <tr>
            <?php if($showDelete) echo '<th></th>'; ?>
            <th>Usuario</th>
            <th>Nombre Completo</th>
            <th>Grupo</th>
            <?php if($showDelete) echo '<th></th>'; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($usuarios)) {
            foreach ($usuarios as $fila) {
                echo '<tr id="'.$fila['id'].'">';
                
                if($showDelete) echo '<td><input type="checkbox" name="registroEliminar[]" value="'.$fila['id'].'" /></td>';
                
                echo '<td>'.$fila['usuario'].'</td>
                    <td>'.htmlentities(($fila['nombre'])).'</td>
                    <td>'.htmlentities(($fila['grupo'])).'</td>';
                
                if($showDelete) echo '<td><a href="'.site_url().DIR_TES.'/usuario_tableta/delete/'.$fila['id'].'/'.$tableta->id.'"
                        class="btn btn-primary" onclick="if(confirm(\'¿Realmente desea eliminar el registro?\')) { return true; } else { return false; }">Eliminar <i class="icon-remove"></i></a></td>';
                '</tr>';
            }
        } else {
            echo '<tr><td colspan="7"><div align="center">No se encontraron registros en la busqueda</div></td></tr>';
        }
        ?>
    </tbody>
</table>
</div>

<?php 
    if($showDelete)
        echo '<button type="submit" class="btn btn-primary" >Eliminar Seleccionados <i class="icon-remove"></i></button>&nbsp; ';
    
    if($showInsert)
        echo '<button id="agregarUsuario" class="btn btn-primary">Asignar nuevo usuario a la Tableta <i class="icon-plus"></i></button>';
?>

</form>
<br />

<script type="text/javascript">
$(function() {
    DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';
    var grupo = $("#id_grupo"),
        usuario = $("#id_usuario");

    allFields = $([]).add(grupo).add(usuario),
    tips = $(".validateTips");

    function updateTips(t) {
        tips.text(t).addClass("ui-state-highlight");
        setTimeout(function() {
            tips.removeClass("ui-state-highlight", 1500);
        }, 500);
    }

    function checkSelect(object, name) {
        if( object.val() == 0 ) {
            updateTips("Debe selecccionar un "+name);
            object.addClass( "ui-state-error" );
            return false;
        } else {
            return true;
        }
    }

    $("#dialog-form").dialog({
        autoOpen: false,
        height: 350,
        width: 400,
        modal: true,
        buttons: {
            "Agregar usuario": function() {
                var validOK = true;
                allFields.removeClass("ui-state-error");

                validOK = validOK && checkSelect(grupo, "Grupo de usuarios");
                validOK = validOK && checkSelect(usuario, "Usuario");

                if(validOK) {
                    $('#form-addUser').submit();
                }
            },
            Cancelar: function() {
                $(this).dialog("close");
                allFields.val("").removeClass("ui-state-error");
            }
        },
        close: function() {
          allFields.val("").removeClass("ui-state-error");
        }
    });

    $("#agregarUsuario").click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        
        $("#dialog-form").dialog("open");
    });
    
    grupo.change(function(e){
        $.ajax({
            type: 'POST',
            url:  '/'+DIR_SIIGS+'/usuario/getActivesByGroup/'+$(this).val(),
            dataType: 'json'
        }).done(function(usuarios){
            $('select[name="id_usuario"] > :not(option[value=0])').remove();

            $.each(usuarios, function(index) {
                option = $('<option />');
                option.val(usuarios[index].id);
                option.text(usuarios[index].nombre+' '+usuarios[index].apellido_paterno+' '+usuarios[index].apellido_materno);

                $('select[name="id_usuario"]').append(option);
            });
        });
    });
});
</script>
  
<div id="dialog-form" title="Asignar usuario a la tableta">
    <p class="validateTips"></p>
    <form name="form-addUser" id="form-addUser" method="post" action="<?php echo site_url().DIR_TES.'/usuario_tableta/insert/'.$tableta->id; ?>">
        <fieldset>
            <label for="id_grupo">Grupo</label>
            <select name="id_grupo" id="id_grupo" class="text ui-widget-content ui-corner-all">
                <option value="0">Seleccione una opción</option>
                <?PHP
                foreach ($grupos as $g) {
                    echo '<option value="'.$g->id.'">'.$g->nombre.'</option>';
                }
                ?>
            </select>
            <br />
            <label for="id_usuario">Usuario</label>
            <select name="id_usuario" id="id_usuario" class="text ui-widget-content ui-corner-all">
                <option value="0">Seleccione una opción</option>
            </select>
        </fieldset>
    </form>
</div>

<br />
<button type="button" name="registrarTableta" id="registrarTableta" class="btn btn-primary" onclick="location.href='<?php echo site_url().DIR_TES; ?>/tableta'" >Regresar al listado de tabletas <i class="icon-arrow-left"></i></button>