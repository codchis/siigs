<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<style>
    label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
  </style>

<h2><?=$title;?></h2>
<?php
if(!empty($msgResult))
    echo '<strong>'.$msgResult.'</strong><br />';

echo form_open(site_url().DIR_TES.'/usuario_tableta/index/'.$tableta->id, array('onsubmit'=>"return confirm('Esta seguro de eliminar los elementos seleccionados');")); 

echo 'Datos generales de la tableta: 

<table>
    <tr>
        <td>Direcci&oacute;n MAC</td>
        <td>Versi&oacute;n</td>
        <td>Ultima actualizaci&oacute;n</td>
        <td>Status</td>
        <td>Tipo Censo</td>
    </tr>
    <tr>
        <td>'.$tableta->mac.'</td>
        <td>'.$tableta->version.'</td>
        <td>'.formatFecha($tableta->ultima_actualizacion).'</td>
        <td>'.$tableta->status.'</td>
        <td>'.$tableta->tipo_censo.'</td>
    </tr>
</table>';
?>

<table border="1">
    <thead>
        <tr>
            <th></th>
            <th>Usuario</th>
            <th>Nombre Completo</th>
            <th>Grupo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($usuarios)) {
            foreach ($usuarios as $fila) {
                echo '<tr id="'.$fila['id'].'">
                    <td><input type="checkbox" name="registroEliminar[]" value="'.$fila['id'].'" /></td>
                    <td>'.$fila['usuario'].'</td>
                    <td>'.htmlentities(($fila['nombre'])).'</td>
                    <td>'.htmlentities(($fila['grupo'])).'</td>
                    <td><a href="'.site_url().DIR_TES.'/usuario_tableta/delete/'.$fila['id'].'/'.$tableta->id.'"
                        onclick="if(confirm(\'Realmente desea eliminar el registro\')) { return true; } else { return false; }">Eliminar</a></td>
                </tr>';
            }
        } else {
            echo '<tr><td colspan="7"><div align="center">No se encontraron registros en la busqueda</div></td></tr>';
        }
        ?>
    </tbody>
</table>

<input type="submit" value="Eliminar Seleccionados" />

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

    $("#agregarUsuario").button().click(function() {
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

<button id="agregarUsuario">Asignar nuevo usuario a la Tableta</button>