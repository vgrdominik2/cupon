var abrirCalendario = function ()
{
    $( "#datepicker" ).show();
    document.getElementById('imgCalendar').attributes[0].value = 'cerrarCalendario()';
}
var cerrarCalendario = function ()
{
    $( "#datepicker" ).hide();
    document.getElementById('imgCalendar').attributes[0].value = 'abrirCalendario()'
}