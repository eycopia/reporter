$(document).ready(function() {
    var notice = generateConfirmModal("Estas seguro de realizar esta accion?", "Si procedes el universo se destruye.");

    notice.on('pnotify.confirm', function() {
        alert('Ok, cool.');

    });
    notice.on('pnotify.cancel', function() {
        alert('Oh ok. Chicken, I see.');
    });

    $("table").on('click', "td a.confirmNotif", function(){
        notice.open();
    });




    $("table").on('click', "td button.noticeNotif", function(){
         var opts = {
                type : 'info',
                title: 'Over Here',
                text: "Check me out. I'm in a different stack."
            };
        PNotify.alert(opts);
    });
});

