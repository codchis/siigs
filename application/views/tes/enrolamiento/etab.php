<div class="sonata-ba-content" style="margin-left:-300px;">    
<div class="connection">
        <form action="http://etab.sm2015.com.mx/admin/login_check" method="post">

            
            <input type="hidden" name="_csrf_token" value="<?php echo $token;?>" />

            <div class="control-group">
                <label for="username">Nombre de usuario:</label>

                <div class="controls">
                    <input type="text" id="username" name="_username" value="<?php echo $user;?>" class="big sonata-medium"/>
                </div>
            </div>

            <div class="control-group">
                <label for="password">Contraseña:</label>

                <div class="controls">
                    <input type="password" id="password" name="_password" value="<?php echo $pass;?>" class="big sonata-medium" />
                </div>
            </div>

            <div class="control-group">
                <label for="remember_me">
                    <input type="checkbox" id="remember_me" name="_remember_me" value="on" />
                    Recordar
                </label>
            </div>

            <div class="form-actions">
                <input type="submit" class="btn btn-primary" id="_submit" name="_submit" value="Entrar" />
            </div>
        </form>
    </div>
</div>
<iframe style="width:0px; height:0px; border:0px;" frameborder="0" id="etab_" src="http://etab.sm2015.com.mx/admin/login"></iframe>
<script>
		setTimeout(function(){document.getElementById("_submit").click()},1500);
</script>