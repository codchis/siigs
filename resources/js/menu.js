 
$(document).ready(function(){
	/*----------------------------Menu-------------------------------*/
	$("ul.menu li a").hover(function() { //Al hacer click se ejecuta...
		
		//Con este codigo aplicamos el movimiento de arriva y abajo para el submenu
		$(this).parent().find("ul.submenu").slideDown('fast').show(); //Menu desplegable al hacer click
 
		$(this).parent().hover(function() {
		}, function(){	
			$(this).parent().find("ul.submenu").slideUp('fast'); //Ocultamos el submenu cuando el raton sale fuera del submenu
		});
 
		}).hover(function() { 
			$(this).addClass("subhover"); //Agregamos la clase subhover
		}, function(){	//Cunado sale el cursor, sacamos la clase
			$(this).removeClass("subhover"); 
	});
	/*----------------------------Boton-------------------------------*/
	$("ul.menu li span").click(function() { //Al hacer click se ejecuta...
		
		//Con este codigo aplicamos el movimiento de arriva y abajo para el submenu
		$(this).parent().find("ul.submenu").slideDown('fast').show(); //Menu desplegable al hacer click
 
		$(this).parent().hover(function() {
		}, function(){	
			$(this).parent().find("ul.submenu").slideUp('fast'); //Ocultamos el submenu cuando el raton sale fuera del submenu
		});
 
		}).hover(function() { 
			$(this).addClass("subhover"); //Agregamos la clase subhover
		}, function(){	//Cunado sale el cursor, sacamos la clase
			$(this).removeClass("subhover"); 
	});
 
		
});