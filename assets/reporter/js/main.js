$(document).ready(function() {
//    PNotify.defaults.styling = 'bootstrap3'; // Bootstrap version 3
//    PNotify.defaults.icons = 'bootstrap3'; // glyphicons
    fixedFooter();
});

function fixedFooter(){
	var $content = $('#content');
	var sizeContent = $content.height();
	var sizeWindow = window.innerHeight;
	if ( ( sizeWindow / sizeContent  ) > 3 ) {
		$content.css('height', ( sizeWindow -  ( sizeContent - 100) )  );
	}
}

function showAlert( type, msg, title) {

    var opts = { 'delay': 5000 };
    var defaultTitle = null;

    switch ( type)  {
        case 'info' : defaultTitle = 'Over here.'; break;
        case 'notice' : defaultTitle = 'Over here.'; break;
        case 'success' : defaultTitle = 'Good news!'; break;
        case 'error' : defaultTitle = 'Oh no!'; break;
    }

    if (title == undefined) {
        title = defaultTitle;
    }

    opts.title = title;
    opts.text = msg;
    opts.type = type;

    new PNotify.alert(opts);
}

function generateConfirmModal(titleModal, messageModal){
    return PNotify.notice({
        title: titleModal,
        text: messageModal,
        hide: false,
        autoDisplay: false,
        stack: {
            'dir1': 'down',
            'modal': true,
            'firstpos1': 25,
            'spacing1' : 25
        },
        modules: {
            Confirm: {
                confirm: true
            },
            Buttons: {
                closer: true,
                sticker: false
            },
            History: {
                history: false
            },
            Callbacks: {
                beforeOpen: function (pnotify) {
                    if ( $( ".ui-pnotify-modal-overlay" ).length < 1 ) {
                        $("<div />", {
                            "class": "ui-pnotify-modal-overlay",
                            "css": {
                                "display": "none",
                                "position": "fixed",
                                "top": "0",
                                "bottom": "0",
                                "right": "0",
                                "left": "0",
                                "background-color": "rgba(0, 0, 0, .4)"
                            }
                        }).appendTo("body").fadeIn("fast");
                    }else{
                        $( ".ui-pnotify-modal-overlay" ).toggleClass("hide");
                    }
                },
                beforeClose: function () {
                    $( ".ui-pnotify-modal-overlay" ).addClass("hide");
                }
            }
        }
    });
}
