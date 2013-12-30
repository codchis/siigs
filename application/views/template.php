<!DOCTYPE html>
<html lang='es'>
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <meta charset='utf-8' />
    <title>SIIGS</title>
    <!--[if lt IE 9]>
    <script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script>
    <![endif]-->
    <meta name='robots' content='index,follow' />
    <meta name='keywords' content=',gobierno, salud' />
    <meta name='language' content='es' />
    <link rel="stylesheet" type="text/css" href="/resources/plantillaSM/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/resources/plantillaSM/css/default.css">
    <link rel="stylesheet" type="text/css" href="/resources/plantillaSM/css/menu.css">
    
    <script src="/resources/js/jquery.js"></script>
	<script src="/resources/js/jquery-ui.min.js"></script>
	<script src="/resources/js/modernizr.js"></script>
	<script src="/resources/js/customscript.js" type="text/javascript"></script>
</head>


<body><!-- ENLACE A PORTALES-->

    <div class="container enlace-portales">

        <div class="row-fluid hidden-phone" style="background:#E4E4E4;" >
            <div class="span6"> <script type='text/javascript' src='http://www.chiapas.gob.mx/quicklink-responsive/portales.js'></script></div>
            <div class="span6"> <script type='text/javascript' src='http://www.chiapas.gob.mx/quicklink-responsive/clima.js'></script></div>
        </div>
    </div>


    <div class='container cont-page'>
        <header>
            
            <?= $header ?>

            <div id="header" >
                <?= $menu ?>
            </div>
          

            <!-- Boletines -->
            <div class="row-fluid hidden-phone " id="boletines" >
                <a href="http://www.icosochiapas.gob.mx/">Sala de Prensa:</a>
                <div>
                    <iframe src="http://www.chiapas.gob.mx/newstickernosune.php" width="600px" height="17"
                            marginwidth="0" marginheight="1" frameborder="0" scrolling="no" margintop="10px"> </iframe>
                </div>
            </div>

        </header>

        <!-- contenido -->

        <div class="row-fluid">
            <div id="full-content" class="span12">
              <div class="span1">
              </div>
                
              <div class="span10 contenido">
                <?= $content ?>
              </div>
            </div>
        </div>

        <?= $footer ?>
    </div>

</body>
</html>
		