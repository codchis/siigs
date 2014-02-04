var objFecha = new Date();

var optionsFecha = {
    changeMonth: false,
    changeYear: false,
    navigationAsDateFormat: true,
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
    beforeShow: function(dateText, inst) {
        $('#'+inst.id).mask("99-99-9999");
        $('#'+inst.id).removeClass('errorInput');
 
    },
    onClose: function(dateText, inst) {
        if(dateText != '__-__-____' && dateText != '') {
            if(!isDate(dateText)) {
                $('#'+inst.id).addClass('errorInput');
            } else {
                $('#alert').html('');
				$('#alert').css('display','');
                $('#'+inst.id).removeClass('errorInput');
				$('#alert').removeClass('warning');
            }
        }
        
        $('#'+inst.id).closest("form").submit(function(event){
            if( $(this).find('input.errorInput').length != 0) {
                showAlerta({mensaje: 'La fecha es incorrecta'});
                event.preventDefault();
                return false;
            }
                
        });
    },
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
    if(typeof(parametros.alert) == 'undefined' || parametros.alert == null){
        if($('#alert').length == 0) {
            $('.contenido').prepend('<div id="alert" class="warning" style="display: none;"></div>');
        }
    }
    
    alert = (parametros.alert) ? parametros.alert : '#alert';
    $("html,body").animate({ scrollTop: $(alert).offset().top}, 1000 );
    
    if(!$(alert).hasClass('warning'))
        $(alert).addClass('warning');
    
    $(alert).html(parametros.mensaje).fadeIn(2000, function(){ setTimeout(function(){ $(alert).fadeOut(2000); }, 1000); });
}
