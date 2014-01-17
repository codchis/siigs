<!-- contenido -->
        <div class="row-fluid">
            <div id="full-content" class="span12">
              <div class="span3 logoInicio">
                    <div id="logosiigs" class="row-fluid contenido">
                   <center> <img src="/resources/plantillaSM/img/siigslogo-02.png" alt="Organismo"></center>
                   <?php $logged=$this->session->userdata(USER_LOGGED); if (!$logged) { ?>
                   <a href='/<?php echo DIR_SIIGS; ?>/usuario/login' class="btn  btn-primary btn-bienvenida" id="btnLogin" >Ingresar al SiiGS</a>
                   <?php } ?>
                  </div>
              </div>
              <div class="span9 portada">
                    <div class="row-fluid contenido" id="content">
                   <img src="/resources/plantillaSM/img/banner.jpg">                
                </div>
              </div>
            </div>
        </div>
        <footer>
            <!-- Prensa -->