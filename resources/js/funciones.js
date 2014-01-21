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
        .click(function(){
            
        })
        .fancybox({
            'width'         : '50%',
            'height'        : '60%',				
            'transitionIn'	: 'elastic',
            'transitionOut'	: 'elastic',
            'type'			: 'iframe',	
        });
});
