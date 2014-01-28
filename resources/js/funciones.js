var objFecha = new Date();

var optionsFecha = {
    changeMonth: true,
    changeYear: true,
    duration: "fast",
    dateFormat: 'dd-mm-yy',
    constrainInput: true,
    firstDay: 1,
    closeText: 'X',
    showOn: 'both',
    buttonImage: '/resources/images/calendar.gif',
    buttonImageOnly: true,
    buttonText: 'Clic para seleccionar una fecha',
    yearRange: '2005:'+objFecha.getFullYear(),
    showButtonPanel: false,
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
};

$(document).ready(function() {
    $("#ayuda")
        .fancybox({
            'width'         : '50%',
            'height'        : '60%',				
            'transitionIn'	: 'elastic',
            'transitionOut'	: 'elastic',
            'type'			: 'iframe',	
            onComplete: function(){
                $('#fancybox-frame').load(function(){
                    $.fancybox.hideActivity();
                });
            }
        });
        
    $("#ayuda").click(function(e) { $.fancybox.showActivity(); });
});

function showAlerta(parametros) {
    alert = (parametros.alert) ? parametros.alert : '#alert';
    $("html,body").animate({ scrollTop: $(alert).offset().top+50}, 1000 );
    $(alert).html(parametros.mensaje).fadeIn(2000, function(){ setTimeout(function(){ $(alert).fadeOut(2000); }, 1000); });
}
