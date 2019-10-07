$(document).ready(function() {
    var notice = generateConfirmModal("Estas seguro de realizar esta accion?", "Si procedes el universo se destruye.");

    notice.on('pnotify.confirm', function() {
        alert('Ok, cool.');
        notice.close();
    });
    notice.on('pnotify.cancel', function() {
        alert('Oh ok. Chicken, I see.');
    });

    var $exTable = $("table");

    $exTable.on('click', "td button.confirmNotif", function(){
        notice.open();
    });




    $exTable.on('click', "td button.infoNotif", function(){
        showAlert('info', 'Has presionado un boton');
    });
    $exTable.on('click', "td button.noticeNotif", function(){
        showAlert('notice', 'Se envio el correo');
    });
    $exTable.on('click', "td button.successNotif", function(){
        showAlert('success', 'Se creo un nuevo registro');
    });
    $exTable.on('click', "td button.errorNotif", function(){
        showAlert('error', 'No se pudo contactar al servidor');
    });
});




