<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
	<title>SIIGS</title>
	<!--iOS/android/handheld specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<link rel="stylesheet" type="text/css" media="all" href="/resources/css/style.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<script src="/resources/js/jquery.js"></script>
	<script src="/resources/js/modernizr.js"></script>
	<script src="/resources/js/customscript.js" type="text/javascript"></script>

	<style type="text/css">
					#header h1, #header h2 {
			text-indent: -999em;
			min-width:200px; margin-top: 0;
			}
			#header h1 a, #header h2 a{
			background: url(/resources/images/logo.png) no-repeat;
			min-width: 200px;
			display: block;
			min-height: 80px;
			line-height: 28px;
			}
							.more a, .bubble a:hover, #commentform input#submit {
				background-color: #79ACCD;
			}
			a, .title a:hover, #navigation ul ul li a:hover, #navigation > ul > li > a:hover {
			color:#79ACCD;
			}
							</style>

	</head>
	<body>
		<header class="main-header">
			<div class="container">
				<div id="header">
					<h1 id="logo">
						<a href="#">SIIGS</a>
					</h1><!-- END #logo -->
					<div class="widget-area widget-header">
                    	<ul>
                     <!--AQUI DEBE IR EL LOGO DEL SISTEMA-->
                        </ul>
                     </div>
				</div><!--#header-->
			</div><!--.container-->
		</header>

		<div class="container">
			<div class="secondary-navigation">
				<nav id="navigation">
<!--					<ul class="menu">
                    	<li class="menu-item"><a href="#">Inicio</a></li>
						<li class="menu-item"><a href="#">Opcion1</a></li>
                        <li class="menu-item">
                            <a href="#">Opcion2</a>
                            <ul><li class="menu-item"><a href="#">Inicio</a></li></ul>
                        </li>
						<li class="menu-item">
                        <a href="#">Opcion3</a>
                        	<ul class="menu-item">
                            	<li class="menu-item"><a href="#">Inicio</a></li>
                                <li class="menu-item"><a href="#">Opcion1</a></li>
                                <li class="menu-item"><a href="#">Opcion2</a>
                                    <ul>
                                        <li class="menu-item"><a href="#">Inicio</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
					</ul>-->
      			<?= $menu ?>
                </nav>
			</div>
		</div>

		<div id="page">
	<div class="content">
		<article class="article">
			<div id="content_box">

				<!--div class="post excerpt last">
					<header>
                        <div class="bubble"><a href="#">12</a></div>
                        <h2 class="title"><a href="#" rel="bookmark">T&iacute;tulo, descripci&oacute;n o breadcrumb</a>
                        </h2>
                        <div class="post-info">
                            <span class="theauthor"><a href="#" rel="author">Dato1</a></span>
                            <time>September 11, 2008</time>
                            <span class="thecategory">
                            <a href="#" rel="category tag">Links relacionados</a>
                            ,
                            <a href="#" rel="category tag">Links relacionados</a></span>
						</div>
					</header>
					<div class="post-content image-caption-format-1">
						<p>contenido contenido contenido contenido contenido contenido contenido
                        contenido contenido contenido contenido contenido contenido contenido contenido
                        contenido contenido contenido contenido contenido contenido contenido contenido
                        contenido contenido contenido contenido contenido contenido contenido contenido
                        contenido contenido contenido contenido contenido contenido contenido contenido
                        contenido contenido contenido contenido contenido contenido contenido contenido
                        contenido contenido contenido contenido contenido contenido contenido contenido
                        contenido contenido contenido contenido contenido contenido contenido contenido
                        contenido contenido contenido contenido contenido contenido contenido contenido
                        contenido contenido contenido contenido contenido contenido contenido contenido
                        </p>
						<p class="more"><a href="#">Botones</a>
					</p>
					</div>
				</div-->
      			<?= $content ?>

				<div class="pnavigation2">
					<div class="nav-previous left"><a href="#">Links adicionales</a></div>
					<div class="nav-next right"></div>
				</div>

			</div>
		</article>

		<!--aside class="sidebar c-4-12">
			<div id="sidebars" class="g">
				<div class="sidebar">
					<ul class="sidebar_list">
						<li class="widget widget-sidebar">
							<form method="get" id="searchform" class="search-form" _lpchecked="1">
							<fieldset>
								<input name="s" id="s" value="B&uacute;squeda..." type="text">
								<input id="search-image" class="sbutton" src="resources/images/search.png" style="border: 0px none; vertical-align: top;" type="image">
							</fieldset>
							</form>
						</li>

						<li class="widget widget-sidebar">
                        <h3>Imagenes</h3>
                        <div class="ad-125">
                        	<ul>
                            	<li class="oddad">
                                	<a href="#"><img src="resources/images/125x125.gif" height="125" width="125"></a>
                                </li>
                                <li class="evenad">
                                	<a href="#"><img src="resources/images/125x125.gif" height="125" width="125"></a>
                                </li>
                             </ul>
                         </div>
                         </li>
						<li class="widget widget-sidebar">		<h3>Links visitados o historial</h3>		<ul>
								<li><a href="#">Link 1</a></li>
								<li><a href="#">Link 2</a></li>
								<li><a href="#">Link 3</a></li>
								</ul>
						</li>
						<li class="widget widget-sidebar">
							<div class="mts-subscribe">
								<form style="" action="#" method="post" target="popupwindow" _lpchecked="1">
								<input value="B&uacute;squeda..." type="text">
                                <input value="Subscribe" type="submit">
								</form>
								<div class="result"></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</aside-->
	</div>
</div>
	<footer>
		<div class="container">
			<div class="footer-widgets">
				<div class="f-widget">
					<div class="widget"><h3>M&aacute;s cosas</h3>
						<div class="textwidget">
                        Contenido Contenido Contenido Contenido Contenido Contenido Contenido Contenido
                        Contenido Contenido Contenido Contenido Contenido Contenido Contenido Contenido
                        Contenido Contenido Contenido Contenido Contenido Contenido Contenido Contenido
                        </div>
					</div>
				</div>
				<div class="f-widget">
					<div class="widget"><h3>Otras cosas :D</h3>
                    <div class="textwidget">
                    	Contenido Contenido Contenido Contenido Contenido Contenido Contenido Contenido
                        Contenido Contenido Contenido Contenido Contenido Contenido Contenido Contenido
                        Contenido Contenido Contenido Contenido Contenido Contenido Contenido Contenido
                    </div>
					</div>
				</div>
				<div class="f-widget last">
					<div class="widget">		<h3>Links</h3>		<ul>
						<li><a href="#">Link 1</a></li>
						<li><a href="#">Link 2</a></li>
						<li><a href="#">Link 3</a></li>
						</ul>
					</div>
				</div>
				<div class="copyrights">
					<div class="row" id="copyright-note">
					<span>&copy; 2013</span> - <span><a href="">SIIGS</a>.</span>
					</div>
					<div class="top"><a href="#">Back to Top ↑</a></div>
				</div>
			</div>
		</div>
	</footer>
</body></html>