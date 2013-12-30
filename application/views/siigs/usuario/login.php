
<!--<table border="1">
	<tr>
		<td><label for="nombre_usuario">Nombre de usuario</label></td>
		<td><input type="text" name="nombre_usuario" value="<?php echo set_value('nombre_usuario', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="clave">Clave</label></td>
		<td><input type="password" name="clave" /></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Entrar" /><td>
	</tr>
    <tr>
		<td colspan=2><a href="/<?php echo DIR_SIIGS?>/usuario/form_init" id='a1'>¿Olvidaste tu contraseña?</a><td>
	</tr>
</table>-->

<div class="span4 logoInicio">
    <div id="logosiigs" class="row-fluid contenido">
    <center> <img src="/resources/plantillaSM/img/siigslogo-02.png" alt="Organismo"></center>
    </div>
</div>
<div class="span6 login">
    <div class="row-fluid contenido" id="content">
        <?php 
            echo validation_errors(); 
            echo form_open(DIR_SIIGS.'/usuario/login', array("class"=>"form-signin")); 
            ?>
            <h2 class="form-signin-heading">INICIAR SESIÓN</h2>
            <?php if(!empty($msgResult))
                echo $msgResult.'<br /><br />';
            ?>
            <input type="text" name="nombre_usuario" value="<?php echo set_value('nombre_usuario', ''); ?>" 
                   class="input-block-level" placeholder="Nombre de Usuario">
            <input type="password" class="input-block-level" name="clave" placeholder="Contraseña">
            <label class="checkbox">
            <input type="checkbox" value="remember-me">Recordar mis datos
            </label>
            <button class="btn btn-primary" type="submit" name="submit" >Entrar</button>
        </form>
        <a href="/<?php echo DIR_SIIGS?>/usuario/form_init">¿Olvidaste tu contraseña? </a>
    </div>
</div>